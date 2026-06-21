<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterStudentRequest extends FormRequest
{
    /**
     * Tout le monde peut accéder à ce formulaire.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation.
     */
    public function rules(): array
    {
        return [
            // Informations personnelles
            'nom'              => ['required', 'string', 'max:100'],
            'prenom'           => ['required', 'string', 'max:100'],
            'sexe'             => ['required', 'in:M,F'],
            'date_naissance'   => ['required', 'date', 'before:-16 years'],
            'nationalite'      => ['required', 'string', 'max:100'],

            // Coordonnées
            'email'            => ['required', 'email', 'unique:users,email'],
            'telephone'        => ['nullable', 'string', 'max:20'],
            'adresse'          => ['nullable', 'string', 'max:255'],

            // Informations académiques
            'matricule'        => ['required', 'string', 'max:50', 'unique:students,matricule'],
            'department_id'    => ['required', 'exists:departments,id'],
            'niveau'           => ['required', 'in:L1,L2,L3,M1,M2,D1,D2,D3'],
            'annee_academique' => ['required', 'string', 'max:20'],

            // Fichiers
            'photo'            => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'carte_etudiante'  => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],

            // Mot de passe
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * Messages d'erreur en français.
     */
    public function messages(): array
    {
        return [
            'nom.required'              => 'Le nom est obligatoire.',
            'prenom.required'           => 'Le prénom est obligatoire.',
            'sexe.required'             => 'Le sexe est obligatoire.',
            'sexe.in'                   => 'Le sexe doit être M ou F.',
            'date_naissance.required'   => 'La date de naissance est obligatoire.',
            'date_naissance.before'     => 'Vous devez avoir au moins 16 ans.',
            'nationalite.required'      => 'La nationalité est obligatoire.',
            'email.required'            => 'L\'email est obligatoire.',
            'email.email'               => 'L\'email n\'est pas valide.',
            'email.unique'              => 'Cet email est déjà utilisé.',
            'matricule.required'        => 'Le matricule est obligatoire.',
            'matricule.unique'          => 'Ce matricule est déjà enregistré.',
            'department_id.required'    => 'Le département est obligatoire.',
            'department_id.exists'      => 'Le département sélectionné est invalide.',
            'niveau.required'           => 'Le niveau est obligatoire.',
            'niveau.in'                 => 'Le niveau sélectionné est invalide.',
            'annee_academique.required' => 'L\'année académique est obligatoire.',
            'photo.image'               => 'La photo doit être une image.',
            'photo.mimes'               => 'La photo doit être en jpg, jpeg ou png.',
            'photo.max'                 => 'La photo ne doit pas dépasser 2MB.',
            'carte_etudiante.required'  => 'La carte étudiante est obligatoire.',
            'carte_etudiante.mimes'     => 'La carte doit être en jpg, jpeg, png ou pdf.',
            'carte_etudiante.max'       => 'La carte ne doit pas dépasser 2MB.',
            'password.required'         => 'Le mot de passe est obligatoire.',
            'password.min'              => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed'        => 'Les mots de passe ne correspondent pas.',
        ];
    }
}