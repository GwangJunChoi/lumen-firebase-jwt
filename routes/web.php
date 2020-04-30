
<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$router->get('/', function () use ($router) {
    //return $router->app->version();
    //echo "base64:$(openssl rand -base64 32)";
    return 'Hello tripNdrip';
});




// $router->post('auth', [
//     'uses' => 'Auth\AuthController@authenticate',
// ]);
//test
use Illuminate\Support\Facades\Auth;
$router->get('user', ['middleware' => 'auth', function () {      
        $user = Auth::user();
        return response()->json(Auth::user());
    }]);

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('login', 'Auth\AuthController@login');
    $router->post('register', 'Auth\AuthController@register');
    $router->post('reset', 'Auth\AuthController@reset');
});