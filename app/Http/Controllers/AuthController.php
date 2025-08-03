<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\LoginUserRequest;
use App\Models\User;
use App\Permissions\V1\Abilities;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;


class AuthController extends Controller
{

    use ApiResponses;
    /**
     * Login
     *
     * Signs In the user Create and Return the api token
     *
     * @unauthenticated
     * @group Authentication
     *
     * @response 200 {
     *  "data" : {
     *  "token" : "{YOUR_AUTH_KEY}"
     * }
     * "message": "Authenticated",
     * "status" : 200
     * }
     */
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'password' => 'required', 'confirmed', 'min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // event(new Registered($user));

        Auth::login($user);
        return $this->ok(
            'New Authenticated',
            [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'token' => $user->createToken(
                    'API token for '. $user->email,
                    Abilities::getAbilities($user),
                    now()->addMonth())->plainTextToken,
                
            ]
        );

    }

    public function login(LoginUserRequest $request) {
        // $request->validate($request->all());

        if (!Auth::attempt($request->only("email","password"))) {
            return $this->error("Invalid Credential", 401);
        }

        $user = User::firstWhere('email', $request->email);

        return $this->ok(
            'Authenticated',
            [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'token' => $user->createToken(
                    'API token for '. $user->email,
                    Abilities::getAbilities($user),
                    now()->addMonth())->plainTextToken,
                
            ]
        );
    }

    /**
     * Logout
     *
     *
     * Signs Out the user and destroy's the api token
     *
     * @group Authentication
     *
     * @response 200 {}
     */
    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        // return $this->ok('');
    }
}
