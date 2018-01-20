<?php

namespace App\Http\Controllers;

use App\Artist;
use App\Http\Resources\ArtistResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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
        if ($user && $user->role === "promoter" && $request->has('only_mine')) {
            return ArtistResource::collection(Artist::whereManagerId($user->id)->get());
        } else return ArtistResource::collection(Artist::all());
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

        return ArtistResource::make(Artist::create($validatedData));
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
        $artist->update($validatedData);
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
            $artist->delete();
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
        return Auth::user()->id === $artist->manager_id;
    }
}
