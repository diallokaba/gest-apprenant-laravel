<?php 

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

class PromotionServiceFacade extends Facade{

    protected static function getFacadeAccessor()
    {
        return 'promotionService';
    }
}