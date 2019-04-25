<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display the user specified resource.
     *
     * @param  string  $userName
     * @return \Illuminate\Http\Response
     */
    public function show($user)
    {
        return view('users.show')->with([
            'user' => $user
        ]);
    }
}
