<?php

namespace App\Http\Controllers;

use App\Artist;
use App\Genre;
use App\Http\Resources\ArtistResource;
use Illuminate\Http\Response;

class GenreArtistsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Genre $genre
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */
    public function index(Genre $genre)
    {
        return ArtistResource::collection($genre->artists);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Genre $genre
     * @param Artist $artist
     * @return \Illuminate\Http\Response
     */
    public function show(Genre $genre, Artist $artist)
    {
        $findArtistById = function ($value, $key) use ($artist) {
            return $value->id === $artist->id;
        };

        return $genre->artists->contains($findArtistById)
            ? ArtistResource::make($artist)
            : response()->json(['message' => 'This artist does not belong to this genre'],
                Response::HTTP_NOT_FOUND);
    }
}
