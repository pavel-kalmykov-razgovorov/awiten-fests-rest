<?php

namespace App\Http\Controllers;

use App\Festival;
use App\Http\Resources\FestivalResource;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FestivalController extends Controller
{
    const PERMISSION_ERROR = 'You are not the promoter of this festival';

    /**
     * Create a new FestivalController instance.
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
        $genres = $this->collectGenresFromRequest($request);
        $queryBuilder = Festival::query();
        if ($request->name) {
            $queryBuilder->where('name', 'like', "%$request->name%");
        }
        if ($request->order) {
            $queryBuilder->orderBy('name', $request->order);
        }
        if ($genres) {
            $queryBuilder->join('festival_genre', "festival_genre.festival_id", "=", "id")
                ->whereIn('genre_id', $genres)
                ->groupBy("id");
        }
        if ($user && $user->role === "promoter" && $request->has('only_mine')) {
            $queryBuilder->where('promoter_id', '=', $user->id);
        }
        return FestivalResource::collection($queryBuilder->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // Metemos en la petición nuevos datos a calzador que generamos nosotros mismos de forma automática
        $request->merge([
            'permalink' => kebab_case($request->name),
            'promoter_id' => Auth::user()->id // El middleware se asegura de que tenga rol "promoter"
        ]);

        $validatedData = $request->validate([
            'name' => 'bail|required|unique:festivals|max:255',
            'permalink' => 'required|unique:festivals|max:255',
            'promoter_id' => 'bail|required|exists:users,id',
            'date' => 'nullable|date',
            'province' => 'nullable|string',
            'location' => 'nullable|string',
        ]);
        $festival = Festival::create($validatedData);
        if ($request->artists) $festival->artists()->sync($request->artists);
        if ($request->genres) $festival->genres()->sync($request->genres);
        Storage::makeDirectory("/festivals/$festival->permalink");
        return FestivalResource::make($festival);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Festival $festival
     * @return Response
     */
    public function show(Festival $festival)
    {
        // Usamos el load para forzar el eager loading
        return FestivalResource::make($festival->load(['artists', 'genres', 'photos', 'posts']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Festival $festival
     * @return Response
     */
    public function update(Request $request, Festival $festival)
    {
        if (!$this->userOwns($festival)) {
            return response()->json(['message' => self::PERMISSION_ERROR], Response::HTTP_UNAUTHORIZED);
        }

        // Metemos en la petición nuevos datos a calzador que generamos nosotros mismos de forma automática
        $request->merge([
            'permalink' => kebab_case($request->name),
        ]);
        // Decidimos si tenemos que buscar si existe el nuevo permalink o no
        // en función de si este ha cambiado respecto al original
        $uniqueSearch = $request->name !== $festival->name ? "|unique:festivals" : "";
        $validatedData = $request->validate([
            'name' => "bail|required$uniqueSearch|max:255",
            'permalink' => "required$uniqueSearch|max:255",
            'date' => 'nullable|date',
            'province' => 'nullable|string',
            'location' => 'nullable|string',
        ]);
        $oldPermalink = $festival->permalink;
        $festival->update($validatedData);
        if ($request->artists) $festival->artists()->sync($request->artists);
        if ($request->genres) $festival->genres()->sync($request->genres);
        Storage::move("festivals/$oldPermalink", "festivals/$festival->permalink");
        return FestivalResource::make($festival)->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Festival $festival
     * @return Response
     */
    public function destroy(Festival $festival)
    {
        if (!$this->userOwns($festival)) {
            return response()->json(['message' => self::PERMISSION_ERROR], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $permalink = $festival->permalink;
            $festival->delete();
            Storage::deleteDirectory("/festivals/$permalink");
            return response(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Festival $festival
     * @return bool
     */
    private function userOwns(Festival $festival): bool
    {
        return Auth::user()->id == $festival->promoter_id;
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
