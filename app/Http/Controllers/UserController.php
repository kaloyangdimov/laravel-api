<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function list(Request $request)
    {
        return view('users.list');
    }

    public function update(User $user)
    {
        # code...
    }
}
