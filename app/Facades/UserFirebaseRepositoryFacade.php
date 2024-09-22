<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

class UserFirebaseRepositoryFacade extends Facade{

    protected static function getFacadeAccessor(){
        return 'userFirebaseRepository';
    }
}