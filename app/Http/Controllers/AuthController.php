<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Factory;

class AuthController extends Controller
{
    private $connexion;
    private $firebaseAuth;
    public function __construct(){
        $this->connexion = env('AUTH_CONFIG') ?: 'passport';

        if($this->connexion == 'firebase'){
            $serviceAccountPath = storage_path('app/gest-apprenant-laravel-firebase-adminsdk-iw06h-7cf3b600e8.json');
            $firebase = (new Factory)->withServiceAccount($serviceAccountPath);
            $this->firebaseAuth = $firebase->createAuth();
        }
    }

    public function login(LoginRequest $request){
        $credentials = $request->only('email', 'password');
        
        if ($this->connexion === 'passport') {
            return $this->passportLogin($credentials);
        } else if ($this->connexion === 'firebase') {
            return $this->firebaseLogin($credentials);
        }
    }

    public function passportLogin($credentials){
        $user = User::where('email', $credentials['email'])->first();
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Login ou mot de passe incorrect'], 401);
        }
        if(Auth::attempt($credentials)){
            /** @var \App\Models\User $user */
            $user = Auth::user();
            //$user->token->revoke();
            $token = $user->createToken('authToken')->accessToken;

            return response()->json(['connexion' => 'locale', 'token' => $token], 200);
        }
        return response()->json(['message' => 'Login ou mot de passe incorrect'], 401);
    }

    public function firebaseLogin($credentials){
        try {
            $firebaseUser = $this->firebaseAuth->signInWithEmailAndPassword($credentials['email'], $credentials['password']);
            $firebaseToken = $firebaseUser->idToken();

            if($firebaseToken){
                return response()->json(['connexion' => 'firestore', 'token' => $firebaseToken], 200);
            }
            return response()->json(['message' => 'Login ou mot de passe incorrect'], 401);
        } catch (\Kreait\Firebase\Exception\AuthException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function logout(){
        auth()->user()->token()->revoke();
    }
}
