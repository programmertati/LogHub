<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SSOController extends Controller
{
    public function callback(Request $request)
    {
        $client = new \GuzzleHttp\Client([ 'verify' => false]);
        try {
            $response = $client->request('POST', 'https://sso.pttati.co.id' . "/oauth/token", [
                'headers' => ['Accept' => 'application/json'],
                'form_params' => [
                    "grant_type" => "authorization_code",
                    "client_id" => '9c88d398-15a3-4378-ac95-4725d530ba45',
                    "client_secret" =>"WKJ0dwv3th8ADqosxxZqnpLTh80RI23cFoF1vXFo",
                    "redirect_uri" => 'https://loghub.pttati.co.id'."/callback",
                    "code" => $request->code
                ]
            ]);
            
            $respon = json_decode($response->getBody()->getContents(), true);
        } catch (\Throwable $th) {
            return $th->getMessage() . '<br>' . '9c88d398-15a3-4378-ac95-4725d530ba45' . '<hr>' . var_dump($request->all());
        }

        $access_token = $respon['access_token'];
        try {
            $response = $client->request('GET', 'https://sso.pttati.co.id' . "/api/user", [
                'headers' => ['Accept' => 'application/json', "Authorization" => "Bearer " . $access_token],
                'form_params' => null
            ]);
            $userArray = json_decode($response->getBody()->getContents(), true);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        try {
            $username = $userArray['username'];
        } catch (\Throwable $th) {
            return redirect("login")->withErrors("Gagal mendapatkan informasi login, coba lagi!");
        }

        $user = User::where("username", $username)->first();

        if (!$user) {
            return 'Username tidak ada!';
        }

        Auth::login($user);
            return redirect('/');
        }
}