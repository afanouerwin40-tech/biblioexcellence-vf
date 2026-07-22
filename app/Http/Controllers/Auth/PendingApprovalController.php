<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;

class PendingApprovalController extends Controller
{
    /**
     * Affiche la page d'attente/statut de validation.
     *
     * Deux sources d'information, combinées :
     * 1. Le flash de session juste après une inscription (registered_*),
     *    disponible uniquement lors du tout premier affichage.
     * 2. L'utilisateur retrouvé via `pending_check_user_id` (posé soit à
     *    l'inscription, soit lors d'une tentative de connexion sur un
     *    compte non approuvé) — permet de relire le statut RÉEL en base
     *    à chaque rechargement de la page, sans avoir besoin d'être
     *    connecté (un compte rejeté/suspendu ne doit pas être authentifié
     *    juste pour consulter son statut).
     */
    public function show()
    {
        $account = null;
        $userId  = session('pending_check_user_id');

        if ($userId && $user = User::find($userId)) {
            $fiche = $user->student ?? $user->teacher;

            $account = [
                'name'             => $user->name,
                'email'            => $user->email,
                'matricule'        => $user->identifier,
                'role'             => match ($user->role_type) {
                    'student' => 'Étudiant',
                    'teacher' => 'Enseignant',
                    default   => ucfirst((string) $user->role_type),
                },
                'status'           => $user->status,
                'rejection_reason' => $user->rejection_reason,
                'login_url'        => route('login'),
            ];
        } elseif (session('registered_email')) {
            // Juste après l'inscription, avant même la première tentative
            // de connexion : on n'a que le flash, le statut est forcément
            // "pending" à ce stade (toute inscription démarre en attente).
            $account = [
                'name'             => session('registered_name'),
                'email'            => session('registered_email'),
                'matricule'        => session('registered_matricule'),
                'role'             => session('registered_role'),
                'status'           => 'pending',
                'rejection_reason' => null,
                'login_url'        => route('login'),
            ];
        }

        return view('auth.pending-approval', ['account' => $account]);
    }
}
