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

        // Vérification du statut du compte
        $statusCheck = $this->checkStatus($user);
        if (! $statusCheck['allowed']) {
            return [
                'success' => false,
                'message' => $statusCheck['message'],
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
     * Vérifie si le compte est autorisé à se connecter.
     */
    private function checkStatus(User $user): array
    {
        return match ($user->status) {
            'approved' => [
                'allowed' => true,
                'message' => '',
            ],
            'pending' => [
                'allowed' => false,
                'message' => 'Votre compte est en attente de validation par un administrateur.',
            ],
            'rejected' => [
                'allowed' => false,
                'message' => 'Votre compte a été refusé. Contactez l\'administration.',
            ],
            'suspended' => [
                'allowed' => false,
                'message' => 'Votre compte a été suspendu. Contactez l\'administration.',
            ],
            default => [
                'allowed' => false,
                'message' => 'Statut de compte inconnu. Contactez l\'administration.',
            ],
        };
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