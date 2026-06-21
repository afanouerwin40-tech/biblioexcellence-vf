<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $bookId = $this->route('book')?->id;

        return [
            'titre'       => ['required', 'string', 'max:255'],
            'author_id'   => ['required', 'exists:authors,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'isbn'        => ['nullable', 'string', 'max:20', 'unique:books,isbn,' . $bookId],
            'editeur'     => ['nullable', 'string', 'max:150'],
            'annee'       => ['nullable', 'integer', 'min:1000', 'max:' . date('Y')],
            'langue'      => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'couverture'  => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'quantite'    => ['required', 'integer', 'min:1'],
            'emplacement' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required'       => 'Le titre est obligatoire.',
            'author_id.required'   => 'L\'auteur est obligatoire.',
            'author_id.exists'     => 'L\'auteur sélectionné est invalide.',
            'category_id.required' => 'La catégorie est obligatoire.',
            'category_id.exists'   => 'La catégorie sélectionnée est invalide.',
            'isbn.unique'          => 'Cet ISBN est déjà enregistré.',
            'annee.max'            => 'L\'année ne peut pas être dans le futur.',
            'couverture.image'     => 'La couverture doit être une image.',
            'couverture.mimes'     => 'La couverture doit être en jpg, jpeg ou png.',
            'couverture.max'       => 'La couverture ne doit pas dépasser 2MB.',
            'quantite.required'    => 'La quantité est obligatoire.',
            'quantite.min'         => 'La quantité doit être au moins 1.',
            'langue.required'      => 'La langue est obligatoire.',
        ];
    }
}