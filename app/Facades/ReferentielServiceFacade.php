<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

class ReferentielServiceFacade extends Facade{

    protected static function getFacadeAccessor(){
        return 'referentielService';
    }
}