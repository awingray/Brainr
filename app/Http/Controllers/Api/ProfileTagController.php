<?php

namespace Brainr\Http\Controllers\Api;

use Brainr\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brainr\Tag;
use Brainr\Profile;

class ProfileTagController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @param  \Brainr\Profile  $profile
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Profile $profile)
    {
        $this->authorize('read', $profile);

        return $profile->tags;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brainr\Profile  $profile
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request, Profile $profile)
    {
        $this->authorize('update', $profile);

        $request->validate([
            'name' => ['required', 'string', 'max:100', 'regex:/^[a-z0-9-]*$/'],
        ], [
            'regex' => 'The :attribute field only allows the symbols a-z, 0-9, and  -.'
        ]);

        $tag = Tag::firstOrCreate($request->all());

        $profile->tags()
                ->attach($tag->id);

        return response($tag, 201);
    }

    /**
     * @param  \Brainr\Profile  $profile
     * @param  \Brainr\Tag  $tag
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Profile $profile, Tag $tag)
    {
        $this->authorize('update', $profile);

        $profile->tags()
                ->detach($tag);

        return response(null, 204);
    }
}
