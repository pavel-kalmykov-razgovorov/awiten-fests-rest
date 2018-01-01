<?php

namespace App\Http\Controllers;

use App\Artist;
use App\Http\Resources\ArtistResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */
    public function index()
    {
        return ArtistResource::collection(Artist::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Metemos en la petición nuevos datos a calzador que generamos nosotros mismos de forma automática
        $request->merge([
            'permalink' => kebab_case($request['name']),
            'manager_id' => User::whereRole('manager')->first()->id // FIXME Obtener desde el Auth el ID del manager
        ]);

        $validatedData = $request->validate([
            'name' => 'bail|required|unique:artists|max:255',
            'permalink' => 'required|unique:artists|max:255',
            'manager_id' => 'bail|required|exists:users,id',
            'country' => 'nullable|string',
            'soundcloud' => 'nullable|string',
            'website' => 'nullable|string',
        ]);

        return ArtistResource::make(Artist::create($validatedData));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Artist $artist
     * @return \Illuminate\Http\Response
     */
    public function show(Artist $artist)
    {
        // Usamos el load para forzar el eager loading de los festivales
        return ArtistResource::make($artist->load('festivals'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Artist $artist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Artist $artist)
    {
        // Metemos en la petición nuevos datos a calzador que generamos nosotros mismos de forma automática
        $request->merge([
            'permalink' => kebab_case($request['name']),
        ]);
        // Decidimos si tenemos que buscar si existe el nuevo permalink o no
        // en función de si este ha cambiado respecto al original
        $uniqueSearch = $request->name !== $artist->name ? "|unique:artists" : "";
        $validatedData = $request->validate([
            'name' => "bail|required$uniqueSearch|max:255",
            'permalink' => "required$uniqueSearch|max:255",
            'country' => 'nullable|string',
            'soundcloud' => 'nullable|string',
            'website' => 'nullable|string',
        ]);
        $artist->update($validatedData);
        return ArtistResource::make($artist)->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Artist $artist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Artist $artist)
    {
        try {
            $artist->delete();
            return response(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
