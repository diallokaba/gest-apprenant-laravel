<?php

namespace App\Models;

interface UserFirebaseInterface{

    public static function findByEmail($email);
}