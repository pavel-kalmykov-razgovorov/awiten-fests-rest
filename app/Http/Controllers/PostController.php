<?php

namespace App\Http\Controllers;

use App\Post;
use App\Festival;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PostResource::collection(Post::all());
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
            'permalink' => kebab_case($request['title']),
            'festival_id' => $request['festival_id']
        ]);

        $validatedData = $request->validate([
            'title' => 'bail|required|unique:photos|max:255',
            'lead' => 'bail|required|max:255',
            'body' => 'bail|required|max:610',
            'permalink' => 'required|unique:photos|max:255',
            'festival_id' => 'bail|required|exists:festivals,id'
        ]);
        return PostResource::make(Post::create($validatedData));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return PostResource::make($post->load(['festival']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->merge([
            'permalink' => kebab_case($request['title']),
        ]);
        // Decidimos si tenemos que buscar si existe el nuevo permalink o no
        // en función de si este ha cambiado respecto al original
        $uniqueSearch = $request->name !== $post->name ? "|unique:posts" : "";
        $validatedData = $request->validate([
            'title' => "bail|required$uniqueSearch|max:255",
            'lead' => "bail|required$uniqueSearch|max:255",
            'body' => "bail|required$uniqueSearch|max:610",
            'permalink' => "required$uniqueSearch|max:255",
        ]);
        $post->update($validatedData);
        return PostResource::make($post)->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        try {
            $post->delete();
            return response(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
