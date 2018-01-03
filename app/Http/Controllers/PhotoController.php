<?php

namespace App\Http\Controllers;

use App\Photo;
use App\Festival;
use App\Http\Resources\PhotoResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PhotoResource::collection(Photo::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge([
            'permalink' => kebab_case($request['name']),
            'festival_id' => $request['festival_id']
        ]);

        $validatedData = $request->validate([
            'name' => 'bail|required|unique:photos|max:255',
            'permalink' => 'required|unique:photos|max:255',
            'festival_id' => 'bail|required|exists:festivals,id'
        ]);
        return PhotoResource::make(Photo::create($validatedData));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Photo $photo
     * @return \Illuminate\Http\Response
     */
    public function show(Photo $photo)
    {
        return PhotoResource::make($photo->load(['festival']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Photo $photo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Photo $photo)
    {
        $request->merge([
            'permalink' => kebab_case($request['name']),
        ]);
        // Decidimos si tenemos que buscar si existe el nuevo permalink o no
        // en función de si este ha cambiado respecto al original
        $uniqueSearch = $request->name !== $photo->name ? "|unique:photos" : "";
        $validatedData = $request->validate([
            'name' => "bail|required$uniqueSearch|max:255",
            'permalink' => "required$uniqueSearch|max:255",
        ]);
        $photo->update($validatedData);
        return PhotoResource::make($photo)->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Photo $photo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Photo $photo)
    {
        try {
            $photo->delete();
            return response(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
