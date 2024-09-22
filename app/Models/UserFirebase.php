<?php

namespace App\Models;

use App\Models\FirebaseModel; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Kreait\Firebase\Factory;

class UserFirebase extends FirebaseModel implements UserFirebaseInterface
{
    use HasFactory;
    protected static $collectionName = 'users';

    public static function findByEmail($email)
    {  
        //dd("Bonjour");
        if (is_null(static::$firestore)) {
            new static(); // Initialisation de Firestore si nécessaire
        }

        try {
            // Recherche du document via le champ `uid`
            $query = static::$firestore
                ->collection(static::$collectionName)
                ->where('email', '=', $email)
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

    public static function query($params)
    {
        if (is_null(static::$firestore)) {
            new static(); // Initialisation de Firestore si nécessaire
        }

        try {
            // Démarrer la requête Firebase
            $query = static::$firestore->collection(static::$collectionName);

            // Vérifier si un filtre par rôle est présent dans les paramètres
            if ($params && $params->input('role')) {
                $role = strtoupper($params->input('role'));
                $query = $query->where('role', '=', $role);
            }

            // Récupérer tous les documents qui correspondent à la requête
            $documents = $query->documents();

            $results = [];
            if (!$documents->isEmpty()) {
                foreach ($documents->rows() as $document) {
                    $results[] = $document->data(); // Stocker les données du document
                }
            }

            return $results; // Retourner tous les résultats
        } catch (\Exception $e) {
            // Gestion des erreurs liées à Firestore
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

   
}
