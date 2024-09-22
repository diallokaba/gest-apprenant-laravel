<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReferentielRequest extends FormRequest
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
            'libelle' => 'required|string',
            'code' => 'required|string',
            'statut' => 'required|in:ACTIF,INACTIF,ARCHIVER',
            'competences' => 'array',
            'competences.*.nom' => 'required|string',
            'competences.*.type' => 'required|in:BACKEND,FRONTEND',
            'competences.*.modules' => 'array',
            'competences.*.modules.*.nom' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'libelle.required' => 'Le libelle est obligatoire.',
            'libelle.string' => 'Le libelle doit être une chaine de caractère.',
            'code.required' => 'Le libelle est obligatoire.',
            'code.string' => 'Le libelle doit être une chaine de caractère.',
            'statut.required' => 'Le statut est obligatoire.',
            'statut.in' => 'Le statut doit être ACTIF, INACTIF ou ARCHIVER.',
            'competences.array' => 'Les détails de la dette doivent être un tableau',
            'competences.*.nom.required' => 'Le nom de la competence est obligatoire',
            'competences.*.type.required' => 'Le type de la competence est obligatoire',
            'competences.*.type.in' => 'Le type de la competence doit être BACKEND ou FRONTEND',
            'competences.*.modules.array' => 'Les modules doivent être un tableau',
            'competences.*.modules.*.nom.required' => 'Le nom du module est obligatoire'
        ];
    }

    function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }
}
