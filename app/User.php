<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Firebase\JWT\JWT;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public static function findByToken($token)
    {
        try {
            $result = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
            return self::find($result->sub);
        } catch (\firebase\JWT\ExpiredException $e) {
        } catch (\Exception $e) {             
        }        
    }

    /**
     * Create a new token.
     * 
     * @param  \App\User   $user
     * @return string
     */
    public function setAuthToken(User $user) 
    {
        $payload = [
            'iss' => env('APP_URL'), // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' => time() + 60 * 60 // Expiration time
        ];
        
        // As you can see we are passing `JWT_SECRET` as the second parameter that will 
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    } 
    
}
