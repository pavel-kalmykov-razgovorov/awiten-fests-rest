<?php

namespace App\Http\Controllers;

use App\Artist;
use App\Festival;
use App\Http\Resources\ArtistResource;
use Illuminate\Http\Response;

class FestivalArtistsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Festival $festival
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Festival $festival)
    {
        return ArtistResource::collection($festival->artists);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Festival $festival
     * @param Artist $artist
     * @return \Illuminate\Http\Response
     */
    public function show(Festival $festival, Artist $artist)
    {
        $findArtistById = function ($value, $key) use ($artist) {
            return $value->id === $artist->id;
        };

        return $festival->artists->contains($findArtistById)
            ? ArtistResource::make($artist)
            : response()->json(['message' => 'This artist is not invited to this festival'],
                Response::HTTP_NOT_FOUND);
    }
}
