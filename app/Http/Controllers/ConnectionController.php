<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetConnectionRequest;
use App\Http\Resources\ConnectionCollection;

class ConnectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(GetConnectionRequest $request): ConnectionCollection
    {
        $connections = $request->user()
            ->connections()
            ->whereBetween('date', [
                $request->input('from_date', now()->subYear(1)), // TODO to admin panel
                $request->input('to_date', now())
            ])
            ->paginate($request->input('per_page', 15)); // TODO to admin panel

        return new ConnectionCollection($connections);
    }
}
