<?php

namespace App\Models;

use App\Models\FirebaseModel; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Kreait\Firebase\Factory;

class Promotion extends FirebaseModel implements PromotionModelInterface
{
    use HasFactory;
    protected static $collectionName = 'promotions';

    public static function customFilter($data)
    {
        return $data;
    }
}
