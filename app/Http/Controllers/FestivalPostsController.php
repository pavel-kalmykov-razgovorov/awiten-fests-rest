<?php

namespace App\Http\Controllers;

use App\Festival;
use App\Http\Resources\PostResource;
use App\Post;
use Illuminate\Http\Response;


class FestivalPostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Festival  $festival
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|Response
     */
    public function index(Festival $festival)
    {
        return PostResource::collection($festival->posts);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Festival  $festival
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Festival $festival, Post $post)
    {
        $findPostById = function ($value, $key) use ($post) {
            return $value->id === $post->id;
        };

        return $festival->posts->contains($findPostById)
            ? PostResource::make($post)
            : response()->json(['message' => 'This post is not added to this festival'],
                Response::HTTP_NOT_FOUND);
    }
}
