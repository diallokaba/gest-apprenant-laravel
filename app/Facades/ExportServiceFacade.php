<?php 

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

class ExportServiceFacade extends Facade{

    protected static function getFacadeAccessor()
    {
        return 'export';
    }
}