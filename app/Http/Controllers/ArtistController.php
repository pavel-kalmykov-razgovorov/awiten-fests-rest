<?php

namespace App\Http\Controllers;

use App\Artist;
use App\Http\Resources\ArtistResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArtistController extends Controller
{
    const PERMISSION_ERROR = 'You are not the manager of this artist';

    /**
     * Create a new ArtistController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:api', 'auth.manager:api'], ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $perPage = intval($request->size);
        $genres = $this->collectGenresFromRequest($request);
        $queryBuilder = Artist::query();
        if ($request->name) {
            $queryBuilder->where('name', 'like', "%$request->name%");
        }
        if ($request->order) {
            $queryBuilder->orderBy('name', $request->order);
        }
        if ($genres) {
            $queryBuilder->join('artist_genre', "artist_genre.artist_id", "=", "id")
                ->whereIn('genre_id', $genres)
                ->groupBy("id");
        }
        if ($user && $user->role === "manager" && $request->has('only_mine')) {
            $queryBuilder->where('manager_id', '=', $user->id);
        }
        return ArtistResource::collection($queryBuilder->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Metemos en la petición nuevos datos a calzador que generamos nosotros mismos de forma automática
        $request->merge([
            'permalink' => kebab_case($request->name),
            'manager_id' => Auth::user()->id // El middleware se asegura de que tenga rol "manager"
        ]);

        $validatedData = $request->validate([
            'name' => 'bail|required|unique:artists|max:255',
            'permalink' => 'required|unique:artists|max:255',
            'manager_id' => 'bail|required|exists:users,id',
            'country' => 'nullable|string',
            'soundcloud' => 'nullable|string',
            'website' => 'nullable|string',
        ]);

        $artist = Artist::create($validatedData);
        if ($request->festivals) $artist->festivals()->sync($request->festivals);
        if ($request->genres) $artist->genres()->sync($request->genres);
        Storage::makeDirectory("/artists/$artist->permalink");
        return ArtistResource::make($artist);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Artist $artist
     * @return \Illuminate\Http\Response
     */
    public function show(Artist $artist)
    {
        // Usamos el load para forzar el eager loading
        return ArtistResource::make($artist->load(['festivals', 'genres']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Artist $artist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Artist $artist)
    {
        if (!$this->userOwns($artist)) {
            return response()->json(['message' => self::PERMISSION_ERROR], Response::HTTP_UNAUTHORIZED);
        }

        // Metemos en la petición nuevos datos a calzador que generamos nosotros mismos de forma automática
        $request->merge([
            'permalink' => kebab_case($request->name),
        ]);
        // Decidimos si tenemos que buscar si existe el nuevo permalink o no
        // en función de si este ha cambiado respecto al original
        $uniqueSearch = $request->name !== $artist->name ? "|unique:artists" : "";
        $validatedData = $request->validate([
            'name' => "bail|required$uniqueSearch|max:255",
            'permalink' => "required$uniqueSearch|max:255",
            'country' => 'nullable|string',
            'soundcloud' => 'nullable|string',
            'website' => 'nullable|string',
        ]);
        $oldPermalink = $artist->permalink;
        $artist->update($validatedData);
        if ($request->festivals) $artist->festivals()->sync($request->festivals);
        if ($request->genres) $artist->genres()->sync($request->genres);
        Storage::move("/artists/$oldPermalink", "/artists/$artist->permalink");
        return ArtistResource::make($artist)->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Artist $artist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Artist $artist)
    {
        if (!$this->userOwns($artist)) {
            return response()->json(['message' => self::PERMISSION_ERROR], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $permalink = $artist->permalink;
            $artist->delete();
            Storage::deleteDirectory("/artists/$permalink");
            return response(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Artist $artist
     * @return bool
     */
    private function userOwns(Artist $artist): bool
    {
        return Auth::user()->id == $artist->manager_id;
    }

    /**
     * @param Request $request
     * @return null|static
     */
    private function collectGenresFromRequest(Request $request)
    {
        $genres = $request->genres
            ? collect(explode(",", $request->genres))
                ->map(function ($genre) {
                    return intval($genre); // cast genre to int
                })
                ->filter(function ($genre) {
                    return $genre > 0; // filter non-existent genre's and parse error's
                })
            : null;
        return $genres;
    }
}
