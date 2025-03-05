<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function dashboardUser(){
        return view('user.dashboard');
    }
}
