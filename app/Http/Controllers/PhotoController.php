<?php

namespace App\Http\Controllers;

use App\Festival;
use App\Http\Resources\PhotoResource;
use App\Photo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PhotoController extends Controller
{
    const PERMISSION_ERROR = 'You are not the promoter of this festival';

    /**
     * Create a new PhotoController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:api', 'auth.promoter:api'], ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $perPage = intval($request->size);
        if ($user && $user->role === "promoter" && $request->has('only_mine')) {
            $getFestivalsIds = function (Festival $festival) {
                return $festival->id;
            };
            return PhotoResource::collection(Photo::whereIn('festival_id', $user->festivals->map($getFestivalsIds))->paginate($perPage));
        } else return PhotoResource::collection(Photo::paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $festival_id = $request->festival_id;
        if (!$this->userOwnsFestival($festival_id)) {
            return response()->json(['message' => self::PERMISSION_ERROR], Response::HTTP_UNAUTHORIZED);
        }

        $request->merge([
            'permalink' => kebab_case($request->name),
            'festival_id' => $festival_id
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
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Photo $photo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Photo $photo)
    {
        if (!$this->userOwnsFestival($photo->festival_id)) {
            return response()->json(['message' => self::PERMISSION_ERROR], Response::HTTP_UNAUTHORIZED);
        }

        $request->merge([
            'permalink' => kebab_case($request->name),
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
        if (!$this->userOwnsFestival($photo->festival_id)) {
            return response()->json(['message' => self::PERMISSION_ERROR], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $photo->delete();
            return response(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $festival_id
     * @return bool
     */
    private function userOwnsFestival($festival_id): bool
    {
        return Auth::user()->festivals->search(function (Festival $festival) use ($festival_id) {
                return $festival->id == $festival_id;
            }) !== false;
    }
}
