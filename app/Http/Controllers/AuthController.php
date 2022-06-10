<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Return the login form
    public function showLoginForm()
    {
        return view('pages.auth.login');
    }

    //Logout
    public function logout(){
        auth("web")->logout();

        return redirect(route('index'));
    }

    //Return the registration form
    public function showRegisterForm()
    {
        return view('pages.auth.register');
    }

    //POST for registration form
    public function registerProcess(Request $request)
    {
        $data = $request->validate([
            "name" => ["required", "string"],
            "email" => ["required", "email", "string", "unique:users,email"],
            "password" => ["required", "confirmed"] //will automatically compare the regular password and the confirmation password
        ]);

        $user = User::create([
            'name' => $data["name"],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        //if register successfully, login and go to main page
        if($user){
            auth("web")->login($user);
        }

        return redirect(route('index'));
    }

    //POST for login form
    public function loginProcess(Request $request){
        $data = $request->validate([
            "email" => ["required", "email", "string"],
            "password" => ["required"]
        ]);

        //if authorised - go to main page, else returns an error
        if(auth("web")->attempt($data)){
            return redirect((route('index')));
        }

        return redirect(route('login'))->withErrors(['email' => 'The user was not found, or the data entered is incorrect.']);
    }
}
