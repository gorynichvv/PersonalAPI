<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{

    public function login(LoginRequest $request): Response
    {

        $user = UserService::checkCredentials($request);

        $token = $user->createToken($request->username)->plainTextToken;

        return response([
            'token' => $token
        ], 201);
    }

    public function logout(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();

        return response([
            'message' => 'Successfully logged out'
        ], 200);
    }
}
