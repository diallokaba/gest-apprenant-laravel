<?php

namespace App\Service;

interface PromotionServiceInterface{
    public function create(array $data);
    public function update(array $data, $id);
    public function find($id);
    public function delete($id);
    public function all();
    public function filter($request);
}