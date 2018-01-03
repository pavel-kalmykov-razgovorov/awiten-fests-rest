<?php

namespace App\Http\Controllers;

use App\Festival;
use App\Photo;
use App\Http\Resources\PhotoResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FestivalPhotosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Festival $festival
     * @return \Illuminate\Http\Response
     */
    public function index(Festival $festival)
    {
        return PhotoResource::collection($festival->photos);
    }

    /**
     * Display the specified resource.
     *
     * @param  Festival $festival
     * @param  Photo $photo
     * @return \Illuminate\Http\Response
     */
    public function show(Festival $festival, Photo $photo)
    {
        $findPhotoById = function ($value, $key) use ($photo) {
            return $value->id === $photo->id;
        };

        return $festival->photos->contains($findPhotoById)
            ? PhotoResource::make($photo)
            : response()->json(['message' => 'This photo is not added to this festival'],
                Response::HTTP_NOT_FOUND);
    }
}
