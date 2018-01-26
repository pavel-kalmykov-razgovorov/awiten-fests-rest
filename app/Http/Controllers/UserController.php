<?php

namespace App\Http\Controllers;

use App\Artist;
use App\Festival;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    const PERMISSION_ERROR = "You can only edit your own user.";

    /**
     * Create a new PostController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => 'store']);
        $this->middleware(['auth:api', 'auth.admin:api'], ['only' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */
    public function index()
    {
        return UserResource::collection(User::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:users|max:255',
            'username' => 'required|unique:users|max:255',
            'email' => 'required|unique:users|email|max:255',
            'password' => 'required|max:255',
            'role' => 'required|in:promoter,manager'
        ]);
        $validatedData['password'] = bcrypt($validatedData['password']);
        return UserResource::make(User::create($validatedData));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if ($user->role === 'promoter') $user->load('festivals');
        if ($user->role === 'manager') $user->load('artists');
        return UserResource::make($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $auth = Auth::user();
        if ($auth->id !== $user->id || $auth->role === 'admin') {
            return response()->json(['message' => self::PERMISSION_ERROR], Response::HTTP_UNAUTHORIZED);
        }
        $uniqueNameSearch = $request->name !== $user->name ? "|unique:users" : "";
        $uniqueUsernameSearch = $request->username !== $user->username ? "|unique:users" : "";
        $uniqueEmailSearch = $request->email !== $user->email ? "|unique:users" : "";
        $validatedData = $request->validate([
            'name' => "required$uniqueNameSearch|max:255",
            'username' => "required$uniqueUsernameSearch|max:255",
            'email' => "required$uniqueEmailSearch|email|max:255",
            'password' => 'required|max:255',
        ]);
        $user->update($validatedData);
        return UserResource::make($user)->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        return response()->json(['message' => "Not yet implemented"], Response::HTTP_NOT_IMPLEMENTED);
        /*$auth = Auth::user();
        if ($auth->id !== $user->id || $auth->role === 'admin') {
            return response()->json(['message' => self::PERMISSION_ERROR], Response::HTTP_UNAUTHORIZED);
        }
        try {
            $user->role === 'promoter'
                ? $user->festivals->each(function (Festival $festival) {
                Storage::deleteDirectory("festivals/$festival->permalink");
            })
                : $user->artists->each(function (Artist $artist) {
                Storage::deleteDirectory("artists/$artist->permalink");
            });
            $user->delete();
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }*/
    }
}
