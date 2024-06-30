<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request): UserResource
    {
        return new UserResource($request->user()->load('session'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request): void
    {
        $validated = $request->validated();

        $prepareKeys = UserService::prepareKeys($validated);

        // ../Observers/UserObserver !!!
        User::find(Auth::id())->update($prepareKeys);
    }
}
