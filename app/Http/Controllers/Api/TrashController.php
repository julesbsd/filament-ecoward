<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class TrashController extends Controller
{
    public function index()
    {
        $trashes = Trash::all();
        return response()->json(['trashes' => $trashes], 200);
    }
}
