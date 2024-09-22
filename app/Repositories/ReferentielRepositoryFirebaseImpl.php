<?php 

namespace App\Repositories;
use App\Models\Referentiel;

class ReferentielRepositoryFirebaseImpl implements ReferentielRepositoryInterface{

    public function create(array $data){
        return Referentiel::create($data);
    }
    public function update(array $data, $uid){
        return Referentiel::update($data, $uid);
    }
    public function find($uid){
        return Referentiel::find($uid);
    }
    public function delete($id){

    }
    public function all(){

    }
    public function filter($request){

    }

    public function query($params){
        return Referentiel::query($params);
    }

    public function findBy($value, $variableName){
        return Referentiel::findBy($value, $variableName);
    }

    public function findByuidAndFilter($id, $params){
        return Referentiel::findByuidAndFilter($id, $params);
    }

    public function archive(){
        return Referentiel::archive();
    }
}