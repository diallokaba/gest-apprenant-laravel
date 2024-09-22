<?php

namespace App\Service;

use App\Facades\ReferentielFirebaseRepositoryFacade as ReferentielRepository;
use Exception;

class ReferentielServiceImpl implements ReferentielServiceInterface
{
    public function __construct()
    {
    }

    public function create(array $data){
        $existingReferentielByLibelle = ReferentielRepository::findBy($data['libelle'], 'libelle');
        if ($existingReferentielByLibelle) {
            throw new Exception('Le libelle existe déjà.');
        }
        $existingReferentielByCode = ReferentielRepository::findBy($data['code'], 'code');
        if ($existingReferentielByCode) {
            throw new Exception('Le reference existe déjà.');
        }
        
        $data['uid'] = uniqid(); 
        
        // Vérification de l'existence de compétences et ajout d'uid pour chaque compétence
        if (isset($data['competences']) && is_array($data['competences'])) {
            foreach($data['competences'] as $key => $competence) {
                // Ajout d'un uid unique pour chaque compétence
                $data['competences'][$key]['uid'] = uniqid();

                // Vérification de l'existence de modules dans la compétence et ajout d'uid pour chaque module
                if (isset($competence['modules']) && is_array($competence['modules'])) {
                    foreach($competence['modules'] as $moduleKey => $module) {
                        // Ajout d'un uid unique pour chaque module
                        $data['competences'][$key]['modules'][$moduleKey]['uid'] = uniqid();
                    }
                }
            }
        }

       
        $referentiel = ReferentielRepository::create($data);
        return  ["referentiel" => $referentiel];
    }

    public function queryFilter($request){
        return ReferentielRepository::query($request);
    }

    public function find($value){
        return ReferentielRepository::find($value);
    }

    public function findByuidAndFilter($id, $params){
        return ReferentielRepository::findByuidAndFilter($id, $params);
    }

    public function findBy($value, $variableDBName){
        return ReferentielRepository::findBy($value, $variableDBName);
    }

    public function update(array $data, $uid){
        $referentiel = ReferentielRepository::find($uid);
        if (!$referentiel) {
            throw new Exception("Le référentiel avec l'ID $uid n'existe pas.");
        }

        // Mises à jour des informations générales du référentiel
        $updatedData = array_merge($referentiel, $data);

        if(isset($data['competences'])){
            foreach($data['competences'] as $competence) {
                if(isset($competence['uid'])) {
                    $this->updateCompetence($updatedData, $competence);
                }else {
                    $this->addCompetence($updatedData, $competence);
                }

                // Gestion des modules dans les compétences
                if (isset($competence['modules'])) {
                    foreach ($competence['modules'] as $module) {
                        if (isset($module['uid'])) {
                            $this->updateModule($updatedData, $competence, $module);
                        } else {
                            $this->addModule($updatedData, $competence, $module);
                        }
                    }
                }
            }
        }

        // Suppression soft des compétences et modules
        if (isset($data['deletedCompetences'])) {
            foreach ($data['deletedCompetences'] as $deletedCompetence) {
                $this->softDeleteCompetence($updatedData, $deletedCompetence);
            }
        }

        if (isset($data['deletedModules'])) {
            foreach ($data['deletedModules'] as $deletedModule) {
                $this->softDeleteModule($updatedData, $deletedModule);
            }
        }

        // Mise à jour finale du référentiel dans Firestore
        $updatedReferentiel = ReferentielRepository::update($updatedData, $uid);
        return ["referentiel" => $updatedReferentiel];
    }

    public function updateCompetence(&$referentiel, $competence) {
        foreach($referentiel['competences'] as &$existingCompetence) {
            if($existingCompetence['uid'] === $competence['uid']) {
                $existingCompetence = array_merge($existingCompetence, $competence);
                break;
            }
        }
    }

    public function addCompetence(&$referentiel, $competence) {
        $competence['uid'] = uniqid();
        $referentiel['competences'][] = $competence;
    }

    public function updateModule(&$referentiel, $competence, $module){
        foreach ($competence['modules'] as &$existingModule) {
            if ($existingModule['uid'] === $module['uid']) {
                $existingModule = array_merge($existingModule, $module);
                break;
            }
        }
    }

    public function addModule(&$referentiel, $competence, $module){
        $module['uid'] = uniqid();
        $competence['modules'][] = $module;
    }

    protected function softDeleteCompetence(&$referentiel, $competenceId) {
        // Marquer une compétence comme supprimée (soft delete)
        foreach ($referentiel['competences'] as &$competence) {
            if ($competence['uid'] === $competenceId) {
                $competence['deleted_at'] = now();
                break;
            }
        }
    }

    protected function softDeleteModule(&$referentiel, $moduleId) {
        // Marquer un module comme supprimé (soft delete)
        foreach ($referentiel['competences'] as &$competence) {
            foreach ($competence['modules'] as &$module) {
                if ($module['uid'] === $moduleId) {
                    $module['deleted_at'] = now();
                    break;
                }
            }
        }
    }

    public function softDelete($uid){
        try {
            // Rechercher le référentiel par UID
            $referentiel = ReferentielRepository::find($uid);
            if (!$referentiel) {
                throw new Exception("Le référentiel avec l'ID $uid n'existe pas.");
            }
    
            // Ajouter la date de suppression
            $referentiel['deleted_at'] = now()->toISOString(); // Format correct pour Firestore

            $referentiel['is_deleted'] = true;
            // Mettre à jour le référentiel avec soft delete
            ReferentielRepository::update($referentiel, $uid);
        } catch (Exception $e) {
            // Relever l'exception pour le contrôleur
            throw new Exception("Erreur lors de la suppression du référentiel : " . $e->getMessage());
        }
    }

    public function archive(){
        return ReferentielRepository::archive();
    }
    
}