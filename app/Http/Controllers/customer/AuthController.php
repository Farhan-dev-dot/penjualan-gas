<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Handle login logic here

        return view('customer.auth.login');
    }

    public function register(Request $request)
    {
        // Handle registration logic here

        return view('customer.auth.register');
    }

    public function loginProses(Request $request) {}
}
