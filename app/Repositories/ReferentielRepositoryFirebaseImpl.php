<?php 

namespace App\Repositories;
use App\Models\Referentiel;

class ReferentielRepositoryFirebaseImpl implements ReferentielRepositoryInterface{

    public function create(array $data){
        return Referentiel::create($data);
    }
    public function update(array $data, $id){

    }
    public function find($id){
        return Referentiel::find($id);
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

    public function findByuidAndFilter($id){
        return Referentiel::findByuidAndFilter($id);
    }
}