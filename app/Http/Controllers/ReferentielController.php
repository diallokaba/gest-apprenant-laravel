<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Facades\ReferentielServiceFacade as ReferentielService;
use App\Http\Requests\ReferentielRequest;

class ReferentielController extends Controller
{
    public function store(ReferentielRequest $request){
        return response()->json(["referentiel" => ReferentielService::create($request->all())], 201);
    }

    public function index(Request $request){
        $referentiels = ReferentielService::queryFilter($request);
        return response()->json(["referentiels" => $referentiels], 200);
    }

    public function findByuid($id){
        $referentiel = ReferentielService::findByuidAndFilter($id);
        return response()->json(["referentiel" => $referentiel], 200);
    }
}
