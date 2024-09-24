<?php

namespace App\Repositories;

use App\Models\Promotion;

class PromotionRepositoryImpl implements PromotionRepositoryInterface{

    public function all(){
        return Promotion::all();
    }

    public function create(array $data){
        return Promotion::create($data);
    }

    public function update(array $data, $id){
        return Promotion::update($data, $id);
    }

    public function find($id){
        return Promotion::find($id);
    }

    public function delete($id){
        return Promotion::delete($id);
    }

    public function filter($request){
        return Promotion::customFilter($request);
    }

    public function findBy($value, $variableName){
        return Promotion::findBy($value, $variableName);
    }
}