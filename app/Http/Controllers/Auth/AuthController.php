<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Traits\Auth\JwtAuthenticates; 
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends BaseController 
{
    use JwtAuthenticates;
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request) 
    {
        $this->middleware('auth', ['only' => [
            'reset',            
        ]]);
        $this->request = $request;
    }
}