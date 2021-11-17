<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function list(Request $request)
    {
        $users = User::all();
        return view('users.list', ['users' => $users]);
    }

    public function view(Request $request)
    {
        $validator = Validator::make(['id' => $request->id], [
            'id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return back();
        }

        $user = User::where('id', $request->id)->first();

        return view('users.edit', ['user' => $user]);
    }

    public function update(UserRequest $user)
    {
       dd($user->all());
    }
}
