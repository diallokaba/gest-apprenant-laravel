<?php

namespace App\Service;

use App\Facades\ReferentielFirebaseRepositoryFacade as ReferentielRepository;

class ReferentielServiceImpl implements ReferentielServiceInterface
{
    public function __construct()
    {
    }

    public function create(array $data){
        $existingReferentielByLibelle = ReferentielRepository::findBy($data['libelle'], 'libelle');
        if ($existingReferentielByLibelle) {
            throw new \Exception('Le libelle existe déjà.');
        }
        $existingReferentielByCode = ReferentielRepository::findBy($data['code'], 'code');
        if ($existingReferentielByCode) {
            throw new \Exception('Le reference existe déjà.');
        }
        
        $data['uid'] = uniqid(); 
        $referentiel = ReferentielRepository::create($data);
        return  ["referentiel" => $referentiel];
    }

    public function queryFilter($request){
        return ReferentielRepository::query($request);
    }

    public function find($value){
        return ReferentielRepository::find($value);
    }

    public function findByuidAndFilter($id){
        return ReferentielRepository::findByuidAndFilter($id);
    }

    public function findBy($value, $variableDBName){
        return ReferentielRepository::findBy($value, $variableDBName);
    }
}