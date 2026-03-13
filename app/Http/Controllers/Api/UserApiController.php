<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => User::select('id','name','email')->orderBy('id','desc')->get(),
        ]);
    }
}
