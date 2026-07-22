<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Tente d'authentifier un utilisateur avec son identifiant.
     * L'identifiant peut être un email, un matricule étudiant,
     * un matricule enseignant ou un matricule professionnel.
     */
    public function attempt(string $identifier, string $password): array
    {
        // Cherche l'utilisateur par email OU par identifier
        $user = User::where('email', $identifier)
                    ->orWhere('identifier', $identifier)
                    ->first();

        // Utilisateur introuvable
        if (! $user) {
            return [
                'success' => false,
                'message' => 'Identifiant ou mot de passe incorrect.',
            ];
        }

        // Mot de passe incorrect
        if (! Hash::check($password, $user->password)) {
            return [
                'success' => false,
                'message' => 'Identifiant ou mot de passe incorrect.',
            ];
        }

        // Compte pas encore approuvé (pending / rejected / suspended) :
        // le mot de passe est correct, donc on sait qui c'est, mais on ne
        // le connecte pas — on le redirige vers la page d'attente qui
        // affichera son statut réel (et le motif si rejeté).
        if ($user->status !== 'approved') {
            session()->put('pending_check_user_id', $user->id);

            return [
                'success'          => false,
                'redirect_pending' => true,
            ];
        }

        // Authentification réussie
        Auth::login($user, remember: true);

        return [
            'success'  => true,
            'redirect' => $this->getRedirectUrl($user),
        ];
    }

    /**
     * Retourne l'URL de redirection selon le rôle de l'utilisateur.
     */
    public function getRedirectUrl(User $user): string
    {
        return match ($user->role_type) {
            'admin'     => '/admin/dashboard',
            'librarian' => '/librarian/dashboard',
            'teacher'   => '/teacher/dashboard',
            'student'   => '/student/dashboard',
            default     => '/dashboard',
        };
    }
}