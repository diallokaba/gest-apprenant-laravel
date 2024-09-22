<?php

namespace App\Service;

interface UserServiceInterface
{
    public function create(array $data);
    public function query($request);
    /*public function update(array $data, $id);
    public function find($id);
    public function delete($id);
    public function all();*/
}