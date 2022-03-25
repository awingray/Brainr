<?php

namespace Brainr\Http\Controllers\Api;

use Brainr\Http\Controllers\Controller;
use Brainr\Profile;
use Brainr\ProfileRelation;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileRelationController extends Controller
{
    /**
     * ProfileRelationController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @param  \Brainr\Profile  $profile
     *
     * @return array
     */
    public function index(Profile $profile)
    {
        $this->authorize('read', $profile);

        return $profile->relations;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brainr\Profile  $profile
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function unrelatedProfiles(Request $request, Profile $profile)
    {
        $this->authorize('read', $profile);

        return $request->user()
                       ->profiles()
                       ->unrelated($profile)
                       ->get();
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
            'name' => ['required', 'string', 'max:100'],
            'to' => [
                'required',
                Rule::exists('profiles', 'id')
                    ->where(function (Builder $query) use ($profile) {
                        $query->where('id', '!=', $profile->id);
                    }),
            ],
        ]);

        $to = Profile::find($request->to);

        $relation = new ProfileRelation($request->all());
        $relation->visible = $profile->published && $to->published;
        $relation->saveOrFail();

        $relation->profiles()
                 ->attach([$profile->id, $to->id]);

        return response($relation, 201);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brainr\Profile  $profile
     * @param  \Brainr\ProfileRelation  $relation
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function update(Request $request, Profile $profile, ProfileRelation $relation)
    {
        $this->authorize('update', $profile);

        $request->validate([
            'name' => ['sometimes', 'string', 'max:100'],
        ]);

        $relation->fill($request->all())
                 ->saveOrFail();

        return response(null, 204);
    }

    /**
     * @param  \Brainr\Profile  $profile
     * @param  \Brainr\ProfileRelation  $relation
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Profile $profile, ProfileRelation $relation)
    {
        $this->authorize('update', $profile);

        $relation->delete();

        return response(null, 204);
    }
}
