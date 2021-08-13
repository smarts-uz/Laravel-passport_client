<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;

class SSOController extends Controller
{

    public function getLogin(Request $request) {
        $request->session()->put("state", $state = Str::random(40));
        $query = http_build_query([
            "client_id" => "942266b1-23ad-406e-b2ae-7f0bfde6c459",
            "redirect_uri" => "http://127.0.0.1:8080/callback",
            "response_type" => "code",
            "scope" => "view-user",
            "state" => "$state"
        ]);

        return redirect("http://127.0.0.1:8000/oauth/authorize?" . $query);
    }

    public function getCallBack(Request $request) {
        $state = $request->session()->pull('state');
    
        throw_unless(strlen($state) > 0 && $state == $request->state, InvalidArgumentException::class);
    
        $response = Http::asForm()->post(
            "http://127.0.0.1:8000/oauth/token",
            [
            "grant_type" => "authorization_code",
            "client_id" => "942266b1-23ad-406e-b2ae-7f0bfde6c459",
            "client_secret" => "ADICAX8dd69Vf9IVympSZbRHSE3zrVDJiXD85aMZ",
            "redirect_uri" => "http://127.0.0.1:8080/callback",
            "code" => $request->code
        ]);
    
        $request->session()->put($response->json());
    
        return redirect(route('sso.connect'));
    }

    public function connectUser(Request $request) {
        $access_token = $request->session()->get("access_token");

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer ". $access_token
        ])->get("http://127.0.0.1:8000/api/user");

        $user_arr =  $response->json();

        try {
            $email = $user_arr['email'];
        } catch (\Throwable $th) {
            return redirect('login')->withError('Failed to get login information');
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            $user = new User;
            $user->name = $user_arr['name'];
            $user->email = $user_arr['email'];
            $user->email_verified_at = $user_arr['email_verified_at'];
            $user->save();
        }
        Auth::login($user);
        return redirect(route('home'));
    }
}
