<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referentiel extends FirebaseModel implements ReferentielModelInterface
{

    use HasFactory;

    protected static $collectionName = 'referentiels';
    
    public static function query($params)
    {
        if (is_null(static::$firestore)) {
            new static(); // Initialisation de Firestore si nécessaire
        }

        try {
            // Démarrer la requête Firebase
            $query = static::$firestore->collection(static::$collectionName);

            // Vérifier si un filtre par rôle est présent dans les paramètres
            if ($params && $params->input('statut')) {
                $statut = strtoupper($params->input('statut'));
                $query = $query->where('statut', '=', $statut);
            }else{
                $query = $query->where('statut', '=', 'ACTIF');
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

    public static function findByuidAndFilter($uid)
    {  
        //dd("Bonjour");
        if (is_null(static::$firestore)) {
            new static(); // Initialisation de Firestore si nécessaire
        }

        try {
            // Recherche du document via le champ `uid`
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
}

