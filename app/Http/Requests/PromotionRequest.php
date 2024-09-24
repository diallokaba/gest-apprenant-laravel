<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PromotionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'libelle' => 'required|string|max:255',
            'dateDebut' => 'required|date',
            'dateFin' => 'nullable|date',
            'duree' => 'nullable|integer|min:1',
            'referentiels' => 'nullable|array',
        ];
    }

    public function messages(): array{
        return [
            'libelle.required' => 'Le libelle est obligatoire.',
            'libelle.string' => 'Le libelle doit être une chaine de caractère.',
            'libelle.max' => 'Le libelle ne doit pas depasser 255 caractères.',
            'dateDebut.required' => 'La date de début est obligatoire.',
            'dateDebut.date' => 'La date de debut doit etre une date valide.',
            'dateFin.date' => 'La date de fin doit etre une date valide.',
            'duree.integer' => 'La durée doit être un entier.',
            'duree.min' => 'La durée doit être d’au moins 1 mois.',
        ];
    }


    public function prepareForValidation(){

        // Si la durée est fournie mais pas la date de fin, calculer la date de fin
        if($this->has('dateDebut') && $this->has('duree') && !$this->has('dateFin')){
            $this->merge(['dateFin' => date('Y-m-d', strtotime("+".$this->duree." months", strtotime($this->dateDebut)))]);
        }elseif($this->has('dateDebut') && $this->has('dateFin') && !$this->has('duree')){
            $duree = (strtotime($this->dateFin) - strtotime($this->dateDebut)) / (60 * 60 * 24) / 30;
            $this->merge(['duree' => (int)$duree]);
            //$this->merge(['duree' => (strtotime($this->dateFin) - strtotime($this->dateDebut)) / (60 * 60 * 24) / 30]);
            //$this->merge(['duree' => (strtotime($this->dateFin) - strtotime($this->dateDebut)) / (60 * 60 * 24)]);
        }
    }

    public function withValidator($validator){
        $validator->after(function ($validator) {
            if ($this->filled('dateDebut') && $this->filled('dateFin')) {
                $dateDebut = strtotime($this->dateDebut);
                $dateFin = strtotime($this->dateFin);
                
                // Vérifier si la date de fin est postérieure à la date de début
                if ($dateFin <= $dateDebut) {
                    $validator->errors()->add('dateFin', 'La date de fin doit être postérieure à la date de début.');
                }

                // Vérifier si la différence est d'au moins 30 jours (environ 1 mois)
                $differenceInDays = ($dateFin - $dateDebut) / (60 * 60 * 24);
                if ($differenceInDays < 30) {
                    $validator->errors()->add('dateFin', 'La différence entre la date de fin et la date de début doit être d\'au moins 1 mois.');
                }
            }
        });
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }
}
