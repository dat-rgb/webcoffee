<?php

namespace App\Http\Controllers\admins\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    public function showLoginForm(){
        return view('admins.auth.login');
    }
}
