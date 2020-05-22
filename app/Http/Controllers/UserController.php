<?php

namespace App\Http\Controllers;

use App\User;

class UserController extends Controller
{

    public function show()
    {
        $users = User::whereHas("roles", function($q){ $q->where("name", "manager"); })->with("drivers")->get();
        return response()->json($users, 200);
    }
}
