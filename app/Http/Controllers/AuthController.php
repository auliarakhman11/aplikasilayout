<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login_page()
    {
        return view('auth.login',['title' => 'Login Page']);
    }

    public function login(Request $request)
    {
         $attributes = $request->validate([
            'username' => ['required'],
            'password' => ['required']
        ]);

        if(Auth::attempt($attributes)){

            $user = User::where('username', $request->username)->first();

            if($user->aktif){
                // if($user->role_id == 4){
                //     User::where('id',$user->id)->update([                        
                //         'aktif' => 0
                //     ]);
                //     return redirect(route('penilaianPegawai'));
                // }else{
                //     return redirect(RouteServiceProvider::HOME);
                // }
                return redirect(RouteServiceProvider::HOME);
            }else{
                return redirect(route('nonActive'));
            }

            

            
        }

        // $user = User::where('username', $request->username)->first();

        // if($user){
        //     if(Hash::check($request->password, $user->password)){
        //         dd($user);
        //         // Auth::login($user);
                
        //         // return redirect(route('dashboard'))->with('success','Selamat datang '. $user->name);
        //     }else{
        //         throw ValidationException::withMessages([
        //             'password' => 'Password salah'
        //         ]);
        //     }
        // }

        throw ValidationException::withMessages([
            'username' => 'Username atau password salah'
        ]);
    }

    public function logout()
    {
        Auth::logout();
        session()->forget(['gudang_id', 'nm_gudang']);
        return redirect(route('loginPage'))->with('success','Logout Berhasil');
    }

    public function nonActive()
    {
        Auth::logout();
        return redirect(route('loginPage'))->with('error','User anda tidak aktif');
    }

    public function block()
    {
        return view('auth.block');
    }
}
