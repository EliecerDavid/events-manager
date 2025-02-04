<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurrentUserController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        return [
            'data' => [
                'id' => $user->id,
                'username' => $user->username,
            ],
        ];
    }
}
