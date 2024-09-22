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

    public static function findByuidAndFilter($uid, $params){
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
                $data = $document->data(); // Récupérer les données du document

                // Vérifier si le paramètre 'module=oui' est présent
                if ($params->input('module') === 'oui') {
                    // Si module=oui, filtrer uniquement les modules et retirer les compétences
                    $modules = [];

                    // Parcourir les compétences pour extraire les modules
                    if (isset($data['competences'])) {
                        foreach ($data['competences'] as $competence) {
                            if (isset($competence['modules'])) {
                                // Ajouter les modules dans la nouvelle structure
                                foreach ($competence['modules'] as $module) {
                                    $modules[] = $module;
                                }
                            }
                        }
                    }

                    // Retirer les compétences et ne garder que les modules
                    unset($data['competences']);
                    $data['modules'] = $modules;
                }

                return $data; // Retourner les données filtrées ou complètes
            } else {
                return null; // Aucun document trouvé
            }
        } catch (\Exception $e) {
            // Gestion des erreurs liées à Firestore
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}

