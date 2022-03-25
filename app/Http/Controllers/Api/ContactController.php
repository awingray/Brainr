<?php

namespace Brainr\Http\Controllers\Api;

use Brainr\Http\Controllers\Controller;
use Brainr\Contact;
use Brainr\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    /**
     * ContactController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Displays all the contact details owned by the profile
     *
     * @param  Profile  $profile
     *
     * @return mixed
     */
    public function index(Profile $profile)
    {
        $this->authorize('update', $profile);

        return $profile->contacts;
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
            'type' => ['required', Rule::in(['website', 'email', 'phone'])],
            'detail' => ['required', 'string', 'max:255'],
        ]);

        $contacts = $profile->contacts()
                            ->create($request->all());

        return response($contacts, 201);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brainr\Profile  $profile
     * @param  \Brainr\Contact  $contact
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Throwable
     */
    public function update(Request $request, Profile $profile, Contact $contact)
    {
        $this->authorize('update', $profile);

        $request->validate([
            'type' => ['required', Rule::in(['website', 'email', 'phone'])],
            'detail' => ['required', 'string', 'max:255'],
        ]);

        $contact->fill($request->all())
                ->saveOrFail();

        return response(null, 204);
    }

    /**
     * @param  \Brainr\Profile  $profile
     * @param  \Brainr\Contact  $contact
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Profile $profile, Contact $contact)
    {
        $this->authorize('update', $profile);

        $contact->delete();

        return response(null, 204);
    }

}
