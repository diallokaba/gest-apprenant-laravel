<?php

namespace App\Http\Middleware;

use App\Models\UserFirebase;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    private $connexiton;

    public function __construct(){
        $this->connexiton = env('AUTH_CONFIG') ?: 'passport';
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        
        if($this->connexiton == 'firebase'){
            $user = $request->attributes->get('firebaseUser');
            if (!$user || !isset($user['role'])) {
                return response()->json(['error' => 'No role found for the user'], 403);
            }
            
            $role = $user['role'];
            if (!in_array($role, $roles)) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        }else if($this->connexiton == 'passport'){
            $user = Auth::user();
            if($user && in_array($user->role_id, $roles)){
                return $next($request);
            }
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return $next($request);
    }
}
