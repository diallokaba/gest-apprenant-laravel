<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

class ReferentielFirebaseRepositoryFacade extends Facade{

    protected static function getFacadeAccessor()
    {
        return 'referentielRepository';
    }
}