<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('user');
    }


    public function userActions($id)
    {
        return view('datatable-user-actions', [
            'id' => $id,
        ]);
    }
}
