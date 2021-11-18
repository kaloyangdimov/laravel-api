<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function list(Request $request)
    {
        $users = User::all();
        return view('users.list', ['users' => $users]);
    }

    public function show(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    public function update(UserRequest $userRequest, User $user)
    {
        $postAttributes = $userRequest->validated();
        $user->update($postAttributes);

        return redirect()->route('user.show', ['user' => $user])->with('status', __('custom.success_update'));
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();

        if (auth()->id() == $user->id) {
            Auth::guard('web')->logout();

            request()->session()->invalidate();

            request()->session()->regenerateToken();

            return redirect('/')->with('status', __('custom.account_deleted'));
        }

        return redirect()->route('users.list')->with('status', __('custom.user_deleted'));
    }
}
