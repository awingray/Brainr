<?php

namespace Brainr\Http\Controllers\Api;

use Brainr\Http\Controllers\Controller;
use Brainr\Profile;
use Brainr\Scopes\TrueColumnScope;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * FavoriteController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');

        Profile::addGlobalScope(new TrueColumnScope('published'));
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        return $request->user()->favorites;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brainr\Profile  $profile
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show(Request $request, Profile $profile)
    {
        return response([
            'favorite' => (bool) $request->user()
                                         ->favorites()
                                         ->where($profile->getKeyName(), $profile->getKey())
                                         ->exists(),
        ], 200);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brainr\Profile  $profile
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile)
    {
        list('attached' => $attached) = $request->user()
                                                ->favorites()
                                                ->toggle($profile);

        return response([
            'favorite' => (bool) $attached,
        ], 201);
    }
}
