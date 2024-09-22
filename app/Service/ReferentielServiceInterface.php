<?php

namespace App\Service;

interface ReferentielServiceInterface{
    public function create(array $data);
    public function findBy($value, $variableDBName);
    public function find($id);
    public function findByuidAndFilter($id);
}