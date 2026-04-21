<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    public function LoginForm()
    {
        return view('auth.login');
    }
    public function LoginAuth(Request $req){
        $email = $req->input('email');
        $password = $req->input('password');
        if(Auth::guard('touchstaraccount')->attempt(['touch_acc_email' => $email, 'password' => $password])){
            return Response::redirectTo("/machine");
        }else{
            return Response::json(['message' => 'Invalid credentials'], 401);
        }
    }

    public function client(){
        return view('clientauth.register');
    }
}

