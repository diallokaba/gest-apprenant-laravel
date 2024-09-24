<?php 

namespace App\Service;

use App\Facades\PromotionFirebaseRepositoryFacade as PromotionRepository;
use App\Facades\ReferentielFirebaseRepositoryFacade as ReferentielRepository;
use App\Models\Promotion;
use Exception;

class PromotionServiceImpl implements PromotionServiceInterface
{
    public function __construct()
    {
    }

    public function create(array $data){
        $libelle = PromotionRepository::findBy($data['libelle'], 'libelle');
        if($libelle){
            throw new Exception("Le libelle de la promotion existe déjà");
        }

        if(!isset($data['dateFin']) && !isset($data['duree'])){
            throw new Exception("Veuillez renseigner une date de fin ou une duree");
        }

        $addedReferentiels =[];
        $failedReferentiels = [];
        if(isset($data['referentiels'])){
            foreach($data['referentiels'] as $referentielUid){
                $referentiel = ReferentielRepository::findBy($referentielUid, 'uid');
                if($referentiel && strtoupper($referentiel['statut']) == 'ACTIF'){
                    $addedReferentiels[] = $referentielUid;
                }else{
                    $failedReferentiels[] = $referentielUid;
                }
            }
            
            $data['referentiels'] = $addedReferentiels;
        }

        $data['statut'] = 'INACTIF';
        $data['photoCouverture'] = 'http://via.placeholder.com/350x150';

        $data['dateDebut'] = (new \DateTime($data['dateDebut']))->format('Y-m-d');
        $data['dateFin'] = (new \DateTime($data['dateFin']))->format('Y-m-d');

        $data['uid'] = uniqid();

        $promoition = PromotionRepository::create($data);
        return ['promotion' => $promoition, 'failedReferentiels' => $failedReferentiels, 'addedReferentiels' => $addedReferentiels];  
    }
    public function update(array $data, $id){
        return null;
    }
    public function find($id){
        return null;
    }
    public function delete($id){
        return null;
    }
    public function all(){
        return null;
    }
    public function filter($request){
        return null;
    }
}