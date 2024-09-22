<?php

namespace App\Service;

use App\Facades\UserRepositoryFacade as UserRepository;
use App\Facades\UserFirebaseRepositoryFacade as UserFirebaseRepository;
use App\Models\Role;
use Exception;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Factory;

use App\Facades\ExportServiceFacade as ExportService;

class UserServiceImpl implements UserServiceInterface{

    private $connexion;
    private $firebaseAuth;
    public function __construct(){
        $serviceAccountPath = storage_path('app/gest-apprenant-laravel-firebase-adminsdk-iw06h-7cf3b600e8.json');
        $firebase = (new Factory)->withServiceAccount($serviceAccountPath);
        $this->firebaseAuth = $firebase->createAuth();
        $this->connexion = env('AUTH_CONFIG') ?: 'passport';
    }

    public function create(array $data)
    {
        try{
            DB::beginTransaction();
            $role = Role::find($data['role']['id']);
            if(!$role) {
                throw new Exception('Role not found');
            }

            if($role->nom == 'ROLE_ADMIN') {
                throw new Exception('Admin cannot be created');
            }

            //ici je veux stocker aussi l'utilisateur firestore comme je le fais dans la BD locale

            $photoUrl = 'https://cdn-icons-png.flaticon.com/128/17346/17346780.png';

            $localUser = UserRepository::create([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'adresse' => $data['adresse'],
                'email' => $data['email'],
                'telephone' => $data['telephone'],
                'fonction' => $data['fonction'],
                'statut' => 'ACTIF',
                'photo' => $photoUrl,
                'role_id' => $data['role']['id'],
                'password' => bcrypt($data['password']),
            ]);

            $firebaseUser = $this->firebaseAuth->createUserWithEmailAndPassword($data['email'], $data['password']);

            // Stockage de l'utilisateur dans Firestore
            $firestoreUser = UserFirebaseRepository::create([
                'uid' => $firebaseUser->uid, // Firebase UID
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'adresse' => $data['adresse'],
                'email' => $data['email'],
                'telephone' => $data['telephone'],
                'fonction' => $data['fonction'],
                'statut' => 'ACTIF',
                'photo' => $photoUrl,
                'role' => $role->nom,
                'created_at' => now()->toDateTimeString(), // Ajout de l'horodatage
                'updated_at' => now()->toDateTimeString(),
            ]);
            DB::commit();

            return [
                'firebaseUser' => $firebaseUser,
                'localUser' => $localUser,
                'firestoreUser' => $firestoreUser,
            ];
        }catch(Exception $e){
            DB::rollBack();
            throw new Exception('Erreur lors de la création de l\'utilisateur : ' . $e->getMessage());
        }
    }

    public function query($request){
        if($this->connexion == 'firebase'){
            return $this->findAllFirebase($request);
        }else{
            return $this->findAllLocal($request);
        }
    }

    public function findAllFirebase($request){
        return UserFirebaseRepository::query($request);
    }

    public function findAllLocal($params){
        try{
            $users = UserRepository::query();
            if($params && $params->input('role')){
                $value =  strtolower($params->input('role'));
                switch (strtoupper($value)) {
                    case 'ADMIN':
                        $users->where('role_id', 1);
                        break;
                    case 'MANAGER':
                        $users->where('role_id', 2);
                        break;
                    case 'CME':
                        $users->where('role_id', 3);
                        break;
                    case 'COACH':
                        $users->where('role_id', 4);
                        break;
                    case 'VIGILE':
                        $users->where('role_id', 5);
                        break;
                    default:
                        break;
                }
            }
            return $users->get();;
        }catch(Exception $e){
            throw new Exception('Erreur lors de la récupération des utilisateurs : ' . $e->getMessage());
        }
    }

    public function update($data, $id){
        try{
            $user = null;
            if($this->connexion == 'firebase'){
                $user = UserFirebaseRepository::update($data, $id);
            }else{
                $user = UserRepository::update($id, $data);
            }
            return $user;
        }catch(Exception $e){
            DB::rollBack();
            throw new Exception('Erreur lors de la mise à jour de l\'utilisateur : ' . $e->getMessage());
        }
    }

    public function exportWithExcel($request){
        if($this->connexion == 'firebase'){
            $users = $this->findAllFirebase($request);
            $excludedFields = ['uid', 'updated_at', 'photo', 'created_at'];
            return ExportService::excelExport($users, $excludedFields, 'users');
        }else{
            $users = $this->findAllLocal($request);
            $excludedFields = ['id', 'updated_at', 'photo', 'created_at'];
            return ExportService::excelExport($users, $excludedFields, 'users');
        }
    }

    public function exportWithPdf($request){
        if($this->connexion == 'firebase'){
            $users = $this->findAllFirebase($request);
            $excludedFields = ['uid', 'updated_at', 'photo', 'created_at'];
            return ExportService::pdfExport($users, $excludedFields, 'users');
        }else{
            $users = $this->findAllLocal($request);
            $excludedFields = ['id', 'updated_at', 'photo', 'created_at'];
            return ExportService::pdfExport($users, $excludedFields, 'users');
        }
    }
}