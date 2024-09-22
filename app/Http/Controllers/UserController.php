<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Facades\UserServiceFacade as UserService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $connexion;
    public function __construct()
    {
        $this->connexion = env('AUTH_CONFIG') ?: 'passport';
    }

    public function store(UserRequest $request){
        if($this->connexion == 'firebase'){
            $connectedUser = $request->attributes->get('firebaseUser');
            if($connectedUser['role'] == 'MANAGER' && $request->role['id'] == '1'){
                return response()->json(['error' => 'Vous n\'avez pas le privilège d\'ajouter un utilisateur avec le role ADMIN'], status: 403);
            }
        }else {
            $connectedUser = Auth::user();
            if($connectedUser->role_id == '2' && $request->role['id'] == '1'){
                return response()->json(['error' => 'Vous n\'avez pas le priviliege d\'ajouter un utilisateur avec le role ADMIN'], status: 403);
            }
        }
        $user = UserService::create($request->all());
        return response()->json(["user" => $user], 201);
    }

    public function index(Request $request){
        $users = UserService::query($request);
        return response()->json(["users" => $users], 200);
    }

    public function update(Request $request, $id){
        $user = UserService::update($request->all(), $id);
        return response()->json(["user" => $user], 201);
    }

    public function exportWithExcel(Request $request){
        $filePath = UserService::exportWithExcel($request);
        return response()->json(['file_path' => $filePath], 200);
    }

    public function exportWithPdf(Request $request){
        $filePath = UserService::exportWithPdf($request);
        return response()->json(['file_path' => $filePath], 200);
    }
}
