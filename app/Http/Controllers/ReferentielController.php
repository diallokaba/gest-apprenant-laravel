<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Facades\ReferentielServiceFacade as ReferentielService;
use App\Http\Requests\ReferentielRequest;
use Exception;

class ReferentielController extends Controller
{
    public function store(ReferentielRequest $request){
        return response()->json(["referentiel" => ReferentielService::create($request->all())], 201);
    }

    public function index(Request $request){
        $referentiels = ReferentielService::queryFilter($request);
        return response()->json(["referentiels" => $referentiels], 200);
    }

    public function findByuid($id, Request $request){
        $referentiel = ReferentielService::findByuidAndFilter($id, $request);
        return response()->json(["referentiel" => $referentiel], 200);
    }

    public function update(Request $request, $id){
        $referentiel = ReferentielService::update($request->all(), $id);
        return response()->json(["referentiel" => $referentiel], 200);
    }

    public function softDelete($uid){
        try {
            ReferentielService::softDelete($uid);
            return response()->json(['message' => 'Referentiel supprimé avec succes'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404); // Gestion des erreurs proprement
        }
    }

    public function archive(){
        $referentiels = ReferentielService::archive();
        return response()->json(["referentiels" => $referentiels], 200);
    }
    
}
