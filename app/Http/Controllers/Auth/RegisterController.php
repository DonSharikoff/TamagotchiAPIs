<?php

namespace App\Http\Controllers\Auth;

use App\Models\Pet;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller {

    public function __construct() {
        $this->middleware('guest');
    }

    private function validator(array $data): \Illuminate\Contracts\Validation\Validator {
        return Validator::make($data, [
            'char_name' => ['required', 'string'],
            'username' => ['required', 'string', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:5', 'confirmed'],
            'img' => ['image'],
        ]);
    }

    public function register(Request $request) {

        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        $this->makePet($request, $user->id);

        $token = Request::create('api/v1/login','POST');
        return Route::dispatch($token);
    }

    private function create(array $data): User {
        return User::create([
            'email' => $data['username'],
            'password' => Hash::make($data['password']),
        ]);
    }

    private function makePet(Request $request, int $user_id): void {
        Pet::create([
            'name' => $request->char_name,
            'path_to_img' => ($request->file('img')) ? $this->saveImg($request) : null,
            'user_id' => $user_id,
        ]);
    }

    private function saveImg(Request $request): string {
        $path = explode('/', $request->file('img')->store('public/avatars'));
        $path[0] = 'storage';
        return implode('/', $path);
    }
}
