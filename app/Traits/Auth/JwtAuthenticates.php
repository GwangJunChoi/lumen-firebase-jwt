<?php

namespace App\Traits\Auth;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Http\Request;

trait JwtAuthenticates 
{

     /**
     * 
     * @return mixed
     */
    public function login() 
    {        
        $this->validate($this->request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);
        // Find the user by email
        $user = User::where('email', $this->request->input('email'))->first();

        if (!$user) {
            return response()->json([
                'error' => 'Email does not exist.'
            ], 400);
        }

        if (self::userHashCheck($user)) {
            return response()->json([
                'token' => $user->setAuthToken($user)
            ], 200);
        }
        
        return response()->json([
            'error' => 'Email or password is wrong.'
        ], 400);
    }

    /**
     * 
     * @return \App\User
     */
    public function register()
    {
        $this->validate($this->request, [
            'email'     => ['required', 'string', 'max:255', 'unique:users'],
            'name'      => ['required', 'string', 'max:255', 'unique:users'],
            'password'  => ['required', 'string', 'min:4', 'confirmed'],
        ]);

        return User::create([
            'email'     => $this->request->input('email'),
            'name'      => $this->request->input('name'),
            'password'  => Hash::make($this->request->input('password')),
        ]);
    }

    /**
     * 
     * @return boolean
     */
    public function reset()
    {
        $this->validate($this->request, [
            'password'  => ['required', 'string'],
            'new_password'  => ['required', 'string', 'min:4', 'confirmed'],
        ]);

        $user = Auth::user();
        if (self::userHashCheck($user)) {
            $user->password = Hash::make($this->request->input('new_password'));        
            return $user->save();
        }
        
        return false;
    }
    
    /**
     * 
     * @return boolean
     */
    protected function userHashCheck(User $user)
    {
        return Hash::check($this->request->input('password'), $user->password);
    }
    
}