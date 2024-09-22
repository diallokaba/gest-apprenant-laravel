<?php 

namespace App\Repositories;

interface ReferentielRepositoryInterface{

    public function create(array $data);
    public function update(array $data, $id);
    public function find($id);
    public function findByuidAndFilter($id);
    public function delete($id);
    public function all();
    public function filter($request);
    public function findBy($value, $variableName);
    public function query($params);
}