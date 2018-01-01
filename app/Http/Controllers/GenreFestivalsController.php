<?php

namespace App\Http\Controllers;

use App\Festival;
use App\Genre;
use App\Http\Resources\FestivalResource;
use Illuminate\Http\Response;

class GenreFestivalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Genre $genre
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */
    public function index(Genre $genre)
    {
        return FestivalResource::collection($genre->festivals);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Genre $genre
     * @param Festival $festival
     * @return \Illuminate\Http\Response
     */
    public function show(Genre $genre, Festival $festival)
    {
        $findFestivalById = function ($value, $key) use ($festival) {
            return $value->id === $festival->id;
        };
        return $genre->festivals->contains($findFestivalById)
            ? FestivalResource::make($festival)
            : response()->json(['message' => 'This festival does not belong to this genre'],
                Response::HTTP_NOT_FOUND);
    }
}
