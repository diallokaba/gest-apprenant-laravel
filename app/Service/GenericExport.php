<?php

namespace App\Service;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GenericExport implements FromArray, WithHeadings, WithStyles
{
    private $data;
    
    public function __construct($data)
    {
        $this->data = $data;
    }

    // Retourner les données sans les clés exclus
    public function array(): array
    {
        return $this->data;
    }

    // Ajouter les en-têtes dynamiquement
    public function headings(): array
    {
        if (!empty($this->data)) {
            // Prendre les clés du premier élément de l'array comme en-têtes
            return array_keys($this->data[0]);
        }
        return [];
    }

    // Styliser les en-têtes pour les mettre en gras
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Appliquer le style gras à la première ligne (les en-têtes)
        ];
    }
}
