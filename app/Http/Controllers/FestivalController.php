<?php

namespace App\Http\Controllers;

use App\Festival;
use App\Http\Resources\FestivalResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FestivalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|Response
     */
    public function index()
    {
        return FestivalResource::collection(Festival::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // Metemos en la petición nuevos datos a calzador que generamos nosotros mismos de forma automática
        $request->merge([
            'permalink' => kebab_case($request['name']),
            'promoter_id' => User::whereRole('promoter')->first()->id // FIXME Obtener desde el Auth el ID del promotor
        ]);

        $validatedData = $request->validate([
            'name' => 'bail|required|unique:festivals|max:255',
            'permalink' => 'required|unique:festivals|max:255',
            'promoter_id' => 'bail|required|exists:users,id',
            'date' => 'nullable|date',
            'province' => 'nullable|string',
            'location' => 'nullable|string',
        ]);
        return FestivalResource::make(Festival::create($validatedData));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Festival $festival
     * @return Response
     */
    public function show(Festival $festival)
    {
        // Usamos el load para forzar el eager loading de los artistas
        return FestivalResource::make($festival->load(['artists', 'genres', 'photos', 'posts']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Festival $festival
     * @return Response
     */
    public function update(Request $request, Festival $festival)
    {
        // Metemos en la petición nuevos datos a calzador que generamos nosotros mismos de forma automática
        $request->merge([
            'permalink' => kebab_case($request['name']),
        ]);
        // Decidimos si tenemos que buscar si existe el nuevo permalink o no
        // en función de si este ha cambiado respecto al original
        $uniqueSearch = $request->name !== $festival->name ? "|unique:festivals" : "";
        $validatedData = $request->validate([
            'name' => "bail|required$uniqueSearch|max:255",
            'permalink' => "required$uniqueSearch|max:255",
            'date' => 'nullable|date',
            'province' => 'nullable|string',
            'location' => 'nullable|string',
        ]);
        $festival->update($validatedData);
        return FestivalResource::make($festival)->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Festival $festival
     * @return Response
     */
    public function destroy(Festival $festival)
    {
        try {
            $festival->delete();
            return response(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
