<?php

namespace App\Repositories;

use App\Models\UserFirebase;

class UserFirebaseRepositoryImpl implements UserFirebaseRepositoryInterface{

    public function all(){
        return UserFirebase::all();
    }

    public function create(array $data){
        return UserFirebase::create($data);
    }

    public function update(array $data, $id){
        return UserFirebase::update($data, $id);
    }

    public function find($id){
        return UserFirebase::find($id);
    }

    public function delete($id){
        return UserFirebase::delete($id);
    }

    public function findByEmail($email){
        return UserFirebase::findByEmail($email);
    }

    public function query($params){
        return UserFirebase::query($params);
    }
}