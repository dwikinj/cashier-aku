<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // register 
    public function registerIndex() {
        return view('backend.auth.register');
    }
    //register end

    //login
    public function loginIndex() {
        return view('backend.auth.login');
    }
    //login end
}
