<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLibrarianRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nom'          => ['required', 'string', 'max:100'],
            'prenom'       => ['required', 'string', 'max:100'],
            'email'        => ['required', 'email', 'unique:users,email'],
            'matricule_pro'=> ['required', 'string', 'max:50', 'unique:librarians,matricule_pro'],
            'telephone'    => ['nullable', 'string', 'max:20'],
            'adresse'      => ['nullable', 'string', 'max:255'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required'          => 'Le nom est obligatoire.',
            'prenom.required'       => 'Le prénom est obligatoire.',
            'email.required'        => 'L\'email est obligatoire.',
            'email.unique'          => 'Cet email est déjà utilisé.',
            'matricule_pro.required'=> 'Le matricule est obligatoire.',
            'matricule_pro.unique'  => 'Ce matricule est déjà enregistré.',
            'password.required'     => 'Le mot de passe est obligatoire.',
            'password.min'          => 'Minimum 8 caractères.',
            'password.confirmed'    => 'Les mots de passe ne correspondent pas.',
        ];
    }
}