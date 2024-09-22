<?php

namespace App\Repositories;

interface UserRepositoryInterface{

    public function all();
    public function create(array $attributes);
    public function update($id, array $attributes);
    public function find($id);
    public function delete($id);
    public function findByEmail($email);
    public function query();
}