<?php 

namespace App\Repositories;

interface ReferentielRepositoryInterface{

    public function create(array $data);
    public function update(array $data, $uid);
    public function find($id);
    public function findByuidAndFilter($id, $params);
    public function delete($id);
    public function all();
    public function filter($request);
    public function findBy($value, $variableName);
    public function query($params);
    public function archive();
}