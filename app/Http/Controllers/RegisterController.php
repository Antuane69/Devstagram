<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index() 
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // dd($request);
        // dd($request->get('name'));

        // Modificar el request
        $request->request->add(['username' => Str::slug($request->username)]);

        //Validacion
        $this->validate($request,[
            'name' => 'required|min:10|max:30',
            'username' => 'required|unique:users|min:2|max:20',
            'email' => 'required|unique:users|email|max:50',
            'password' => 'required|confirmed|min:6|max:15'
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        /* autenticar usuario
        auth()->attempt(
            [
                'email' => $request->email,
                'password' => $request->password
            ]
        );*/

        // Otra forma de autenticar
        auth()->attempt($request->only('email','password'));

        //Redireccionar
        return redirect()->route('posts.index', auth()->user()->username);
    }
}