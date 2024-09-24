<?php

namespace App\Http\Controllers;

use App\Http\Requests\PromotionRequest;
use App\Facades\PromotionServiceFacade as PromotionService;

class PromotionController extends Controller
{
    public function store(PromotionRequest $request){
        $promotion = PromotionService::create($request->all());
        return response()->json( $promotion, 201);
    }
}
