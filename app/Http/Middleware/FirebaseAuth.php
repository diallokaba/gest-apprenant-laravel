<?php

namespace App\Http\Middleware;

use App\Models\UserFirebase;
use Closure;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Symfony\Component\HttpFoundation\Response;
use Kreait\Firebase\Exception\Auth\InvalidToken; 

class FirebaseAuth
{

    private $auth;

    public function __construct()
    {
        $serviceAccountPath = storage_path('app/gest-apprenant-laravel-firebase-adminsdk-iw06h-7cf3b600e8.json');
        $firebase = (new Factory)->withServiceAccount($serviceAccountPath)->withProjectId('gest-apprenant-laravel'); // Ajoute cette ligne si nécessaire
        $this->auth = $firebase->createAuth();
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        try {
            // Vérification du token Firebase
            $verifiedIdToken = $this->auth->verifyIdToken($token);
            $firebaseUser = $verifiedIdToken->claims();
            //$user = UserFirebase::findByEmail($firebaseUser->get(name: "email"));
            $user = UserFirebase::find($firebaseUser->get("user_id"));
            $request->attributes->add(['firebaseUser'  => $user]);
        } catch (InvalidToken $e) { // Gestion de l'exception InvalidToken
            return response()->json(['error' => 'Invalid Token'], 401);
        }

        return $next($request);
    }
}
