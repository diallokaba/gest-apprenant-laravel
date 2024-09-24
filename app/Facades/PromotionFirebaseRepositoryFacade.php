<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

class PromotionFirebaseRepositoryFacade extends Facade{

    protected static function getFacadeAccessor()
    {
        return 'promotionRepository';
    }
}