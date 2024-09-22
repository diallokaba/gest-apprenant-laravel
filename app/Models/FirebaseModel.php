<?php

namespace App\Models;

use Kreait\Firebase\Factory;

abstract class FirebaseModel implements FirebaseModelInterface{
    
    protected static $firestore;
    protected static $collectionName;

    public function __construct(){

        if (is_null(static::$firestore)) {
            $serviceAccountPath = storage_path('app/gest-apprenant-laravel-firebase-adminsdk-iw06h-7cf3b600e8.json');
            $firebase = (new Factory())->withServiceAccount($serviceAccountPath); // Utilise config
            static::$firestore = $firebase->createFirestore()->database();
        }
    }

    public static function create(array $data){
        if (is_null(static::$firestore)) {
            // S'assurer que Firestore est initialisé dans le cas d'un appel statique
            new static();
        }
        return static::$firestore->collection(static::$collectionName)->add($data);
    }

    public static function update(array $data, $uid){
        if (is_null(static::$firestore)) {
            new static();
        }
    
        try {
            // Rechercher le document via le champ 'uid'
            $query = static::$firestore
                ->collection(static::$collectionName)
                ->where('uid', '=', $uid)
                ->documents();
    
            if (!$query->isEmpty()) {
                // Récupérer le premier document correspondant
                $document = $query->rows()[0];
    
                // Mettre à jour ce document avec les nouvelles données
                $documentRef = static::$firestore
                    ->collection(static::$collectionName)
                    ->document($document->id());
    
                $documentRef->set($data, ['merge' => true]);
    
                // Retourner les données mises à jour
                return $documentRef->snapshot()->data();
            } else {
                return null; // Aucun document trouvé
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public static function find($uid)
    {   
        if (is_null(static::$firestore)) {
            new static(); // Initialisation de Firestore si nécessaire
        }

        try {
           
            $query = static::$firestore
                ->collection(static::$collectionName)
                ->where('uid', '=', $uid)
                ->documents();

                
            // Vérification si des documents existent
            if (!$query->isEmpty()) {
                $document = $query->rows()[0]; // Récupérer le premier document correspondant
                return $document->data(); // Retourner les données du document
            } else {
                return null; // Aucun document trouvé
            }
        } catch (\Exception $e) {
            // Gestion des erreurs liées à Firestore
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public static function delete($id){
        if (is_null(static::$firestore)) {
            // S'assurer que Firestore est initialisé dans le cas d'un appel statique
            new static();
        }
        return static::$firestore->collection(static::$collectionName)->document($id)->delete();
    }

    public static function all(){
        return null;
    }

    public static function findBy($value, $variableDBName){
        if (is_null(static::$firestore)) {
            new static(); // Initialisation de Firestore si nécessaire
        }

        try {
            // Recherche du document via le champ `uid`
            $query = static::$firestore
                ->collection(static::$collectionName)
                ->where($variableDBName, '=', $value)
                ->documents();

            // Vérification si des documents existent
            if (!$query->isEmpty()) {
                $document = $query->rows()[0]; // Récupérer le premier document correspondant
                return $document->data(); // Retourner les données du document
            } else {
                return null; // Aucun document trouvé
            }
        } catch (\Exception $e) {
            // Gestion des erreurs liées à Firestore
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
}