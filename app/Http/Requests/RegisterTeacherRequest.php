<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom'                  => ['required', 'string', 'max:100'],
            'prenom'               => ['required', 'string', 'max:100'],
            'email'                => ['required', 'email', 'unique:users,email'],
            'telephone'            => ['nullable', 'string', 'max:20'],
            'adresse'              => ['nullable', 'string', 'max:255'],
            'department_id'        => ['required', 'exists:departments,id'],
            'grade'                => ['required', 'in:Assistant,Maître Assistant,Maître de Conférences,Professeur'],
            'matricule_pro'        => ['required', 'string', 'max:50', 'unique:teachers,matricule_pro'],
            'specialite'           => ['nullable', 'string', 'max:150'],
            'photo'                => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'carte_professionnelle' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'password'             => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required'                   => 'Le nom est obligatoire.',
            'prenom.required'                => 'Le prénom est obligatoire.',
            'email.required'                 => 'L\'email est obligatoire.',
            'email.email'                    => 'L\'email n\'est pas valide.',
            'email.unique'                   => 'Cet email est déjà utilisé.',
            'department_id.required'         => 'Le département est obligatoire.',
            'department_id.exists'           => 'Le département sélectionné est invalide.',
            'grade.required'                 => 'Le grade est obligatoire.',
            'grade.in'                       => 'Le grade sélectionné est invalide.',
            'photo.image'                    => 'La photo doit être une image.',
            'photo.mimes'                    => 'La photo doit être en jpg, jpeg ou png.',
            'photo.max'                      => 'La photo ne doit pas dépasser 2MB.',
            'carte_professionnelle.required' => 'La carte professionnelle est obligatoire.',
            'carte_professionnelle.mimes'    => 'La carte doit être en jpg, jpeg, png ou pdf.',
            'carte_professionnelle.max'      => 'La carte ne doit pas dépasser 2MB.',
            'password.required'              => 'Le mot de passe est obligatoire.',
            'password.min'                   => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed'             => 'Les mots de passe ne correspondent pas.',
        ];
    }
}