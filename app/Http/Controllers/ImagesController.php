<?php

namespace App\Http\Controllers;

use App\Artist;
use App\Festival;
use App\Photo;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ImagesController extends Controller
{
    const ARTISTS_FOLDER = "artists";
    const FESTIVALS_FOLDER = "festivals";

    /**
     * Display the artist's profile picture.
     *
     * @param Artist $artist
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function getProfileImage(Artist $artist)
    {
        $path = self::ARTISTS_FOLDER . "/$artist->permalink/$artist->pathProfile";
        return $this->returnImageIfExists($path);
    }

    /**
     * @param $path
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    private function returnImageIfExists($path)
    {
        return Storage::exists($path) ? Storage::response($path) : response(null, Response::HTTP_NOT_FOUND);
    }

    /**
     * Display the artist's header picture.
     *
     * @param Artist $artist
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function getHeaderImage(Artist $artist)
    {
        $path = self::ARTISTS_FOLDER . "/$artist->permalink/$artist->pathHeader";
        return $this->returnImageIfExists($path);
    }

    /**
     * Display the festival's logo picture.
     *
     * @param Festival $festival
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function getLogoImage(Festival $festival)
    {
        $path = self::FESTIVALS_FOLDER . "/$festival->permalink/$festival->pathLogo";
        return $this->returnImageIfExists($path);
    }

    /**
     * Display a festival's picture.
     *
     * @param Photo $photo
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function getFestivalPhoto(Photo $photo)
    {
        $path = self::FESTIVALS_FOLDER . "/" . $photo->festival->permalink . "/$photo->filename";
        return $this->returnImageIfExists($path);
    }
}
