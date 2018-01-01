<?php

namespace App\Http\Controllers;

use App\Artist;
use App\Festival;
use App\Http\Resources\FestivalResource;
use Illuminate\Http\Response;

class ArtistFestivalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Artist $artist
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Artist $artist)
    {
        return FestivalResource::collection($artist->festivals);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Artist $artist
     * @param Festival $festival
     * @return FestivalResource
     */
    public function show(Artist $artist, Festival $festival)
    {
        $findFestivalById = function ($value, $key) use ($festival) {
            return $value->id === $festival->id;
        };
        return $artist->festivals->contains($findFestivalById)
            ? FestivalResource::make($festival)
            : response()->json(['message' => 'This festival does not have this artist as a guest'],
                Response::HTTP_NOT_FOUND);
    }
}
