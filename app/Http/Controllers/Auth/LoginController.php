<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;

class LoginController extends Controller {

    public function login(Request $request) {

        $http = new \GuzzleHttp\Client;

        try {
            $response = $http->post(config('services.passport.login_endpoint'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username' => $request->username,
                    'password' => $request->password
                ]
            ]);
            return response()->json(json_decode($response->getBody(), true));
        } catch (BadResponseException $exception) {
            $message = ($exception->getCode() === 400) ? 'Invalid Request. Please enter a username or a password' : 'Your credentials is incorrect. Please try again';
            return response()->json($message, $exception->getCode());
        }
    }
}
