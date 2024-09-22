<?php 

namespace App\Service;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ExportService{

    private static $connexion;
    public function __construct(){
        static::$connexion = env('AUTH_CONFIG') ?: 'passport';
    }
    public static function excelExport($data, $excludedFields=[], $fileName){

        $cleanData = self::cleanData($data, $excludedFields);

        // Utilisation de Laravel Excel pour enregistrer le fichier sur le disque local
        $filePath = "exports/{$fileName}.xlsx";
        Excel::store(new GenericExport($cleanData), $filePath, 'local'); // Enregistrer localement dans le disque local

        // Retourner le chemin du fichier enregistré
        return $filePath;
    }

    public static function pdfExport($data, $excludedFields=[], $fileName){
        $cleanData = self::cleanData($data, $excludedFields);

        // Utilisation de DomPDF pour générer le fichier PDF
        $pdf = Pdf::loadView('exports.generic', ['data' => $cleanData]);

        $filePath = "exports/{$fileName}.pdf";
        Storage::disk('local')->put($filePath, $pdf->output());
        // Retourner le chemin du fichier enregistré
        return $filePath;
    }

    private static function cleanData($data, $excludedFields)
    {
        // Convertir la collection en tableau
        if(static::$connexion == 'passport'){
            $data = $data->toArray();
        }
        return array_map(function($item) use ($excludedFields) {
            // Suppression des champs exclus
            foreach ($excludedFields as $field) {
                unset($item[$field]);
            }
            return $item;
        }, $data);
    }
}