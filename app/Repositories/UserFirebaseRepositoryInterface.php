<?php 

namespace App\Repositories;

interface UserFirebaseRepositoryInterface{

    public function all();
    public function create(array $attributes);
    public function update(array $attributes, $id);
    public function find($id);
    public function delete($id);
    public function findByEmail($email);
    public function query($params);

}