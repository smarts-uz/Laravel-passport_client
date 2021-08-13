<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\SSO\SSOController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/sso/login', [SSOController::class, 'getLogin'])->name('sso.login');
Route::get('/callback', [SSOController::class, 'getCallBack'])->name('sso.callback');
Route::get('/sso/connect', [SSOController::class, 'connectUser'])->name('sso.connect');

Auth::routes(['register' => false, 'reset' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');



// Route::get('/login', function (Request $request) {
//     $request->session()->put("state", $state = Str::random(40));
//     $query = http_build_query([
//         "client_id" => "942266b1-23ad-406e-b2ae-7f0bfde6c459",
//         "redirect_uri" => "http://127.0.0.1:8080/callback",
//         "response_type" => "code",
//         "scope" => "view-user",
//         "state" => "$state"
//     ]);

//     return redirect("http://127.0.0.1:8000/oauth/authorize?" . $query);
// });

// Route::get('/callback', function(Request $request) {
//     $state = $request->session()->pull('state');
    
//     throw_unless(strlen($state) > 0 && $state == $request->state,
//     InvalidArgumentException::class);

//     $response = Http::asForm()->post(
//         "http://127.0.0.1:8000/oauth/token",
//         [
//         "grant_type" => "authorization_code",
//         "client_id" => "942266b1-23ad-406e-b2ae-7f0bfde6c459",
//         "client_secret" => "ADICAX8dd69Vf9IVympSZbRHSE3zrVDJiXD85aMZ",
//         "redirect_uri" => "http://127.0.0.1:8080/callback",
//         "code" => $request->code
//     ]);

//     $request->session()->put($response->json());

//     return redirect("/authuser");
// });

// Route::get('/authuser', function(Request $request) {
//     $access_token = $request->session()->get("access_token");

//     $response = Http::withHeaders([
//         "Accept" => "application/json",
//         "Authorization" => "Bearer ". $access_token
//     ])->get("http://127.0.0.1:8000/api/user");

//     return $response->json();
// });

