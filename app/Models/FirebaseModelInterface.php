<?php 

namespace App\Models;

interface FirebaseModelInterface
{
    public static function create(array $attributes);
    public static function update(array $attributes, $id);
    public static function find($id);
    public static function delete($id);
    public static function all();
    public static function findBy($value, $variableName);
    public static function archive();
}