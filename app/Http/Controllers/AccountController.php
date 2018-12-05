<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:80',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $attributes = array_merge($request->only('name', 'email'), [
            'password' => bcrypt($request->input('password')),
            'role' => User::READER,
            'api_token' => str_random(60),
        ]);

        $user = User::create($attributes);

        return response()->json([
            'data' => $user
        ], 200);
    }

    /**
     * Display the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return response()->json([
            'data' => Auth::user()
        ]);
    }
}
