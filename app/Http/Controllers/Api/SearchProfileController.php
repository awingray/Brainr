<?php

namespace Brainr\Http\Controllers\Api;

use Brainr\Http\Controllers\Controller;
use Brainr\Profile;
use Brainr\ProfileRelation;
use Brainr\Scopes\TrueColumnScope;
use Illuminate\Http\Request;

class SearchProfileController extends Controller
{
    /**
     * SearchProfileController constructor.
     */
    public function __construct()
    {
        Profile::addGlobalScope(new TrueColumnScope('published'));
        ProfileRelation::addGlobalScope(new TrueColumnScope('visible'));
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        return Profile::query()
                      ->selectRaw('*, MATCH(`name`, `description`, `content`) AGAINST (? IN BOOLEAN MODE) AS relevance',
                          [$request->get('query')])
                      ->whereRaw('MATCH(`name`, `description`, `content`) AGAINST (? IN BOOLEAN MODE)',
                          [$request->get('query')])
                      ->orderByDesc('relevance')
                      ->paginate();
    }

    /**
     * @param  \Brainr\Profile  $profile
     *
     * @return \Brainr\Profile
     */
    public function show(Profile $profile)
    {
        return $profile->load(['relations', 'tags', 'contacts', 'files'])
                       ->makeVisible(['relations', 'tags', 'contacts', 'files']);
    }
}
