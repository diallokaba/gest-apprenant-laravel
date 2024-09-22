<?php

namespace App\Repositories;

use App\Models\User;

class UserRepositoryImpl implements UserRepositoryInterface{

    public function all(){
        return User::all();
    }

    public function create(array $data){
        return User::create($data);
    }

    public function update( $id, array $data){
        return User::where('id', $id)->update($data);
    }

    public function find($id){
        return User::find($id);
    }

    public function delete($id){
        return User::destroy($id);
    }

    public function findByEmail($email){
        return User::where('email', $email)->first();
    }

    public function query(){
        return User::query();
    }
}