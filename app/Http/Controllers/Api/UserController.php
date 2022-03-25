<?php

namespace Brainr\Http\Controllers\Api;

use Brainr\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return mixed
     */
    public function show(Request $request)
    {
        return $request->user();
    }
}
