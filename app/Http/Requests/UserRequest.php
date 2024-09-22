<?php

namespace App\Http\Requests;

use App\Rules\CustomPasswordRule;
use App\Rules\TelephoneRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
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
            'nom' => 'required|string|min:2|max:255',
            'prenom' => 'required|string|min:2|max:255',
            'adresse' => 'required|string|min:2|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'telephone' => ['required', 'unique:users,telephone', new TelephoneRule()],
            //'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'fonction' => 'required|string|max:255',
            'password' =>['required', 'confirmed', new CustomPasswordRule()],
            'password_confirmation' => 'required_with:password',
            'role.id' => ['required', 'integer', 'exists:roles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.min' => 'Le nom doit avoir au moins 2 caractères.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'prenom.min' => 'Le prénom doit avoir au moins 2 caractères.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être une adresse email valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'telephone.required' => 'Le numéro de téléphone est obligatoire.',
            'telephone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
            //'photo.image' => 'Le fichier doit être une image.',
            //'photo.mimes' => 'La photo doit être un fichier de type : jpeg, png, jpg, gif.',
            //'photo.max' => 'La photo ne peut pas dépasser 2 Mo.',
            'fonction.required' => 'La fonction est obligatoire.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'Les mots de passe ne concordent pas.',
            'password_confirmation.required_with' => 'La confirmation du mot de passe est obligatoire lorsque le mot de passe est renseigné.',
            'role.id.required' => 'Le rôle est obligatoire.',
            'role.id.exists' => 'Le rôle sélectionné n\'existe pas.',
        ];
    }

    function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }
}
