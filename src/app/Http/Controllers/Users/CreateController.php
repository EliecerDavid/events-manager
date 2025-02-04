<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreateController extends Controller
{
    public function __invoke(Request $request)
    {
        if (Auth::user()) {
            throw new AuthorizationException();
        }

        $validated = $request->validate([
            'username' => 'required|string|unique:users',
            'password' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone_number' => 'nullable|string',
        ]);

        $user = new User();

        $user->fill($validated)
            ->save();

        $user->wasRecentlyCreated = false;
        return new UserResource($user);
    }
}
