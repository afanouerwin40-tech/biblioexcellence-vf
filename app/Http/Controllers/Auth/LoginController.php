<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(private AuthService $authService)
    {
        // Injection de dépendance — Laravel instancie AuthService automatiquement
    }

    /**
     * Affiche le formulaire de connexion.
     */
    public function create()
    {
        // Si déjà connecté, redirige vers le bon dashboard
        if (Auth::check()) {
            return redirect($this->authService->getRedirectUrl(Auth::user()));
        }

        return view('auth.login');
    }

    /**
     * Traite la soumission du formulaire de connexion.
     */
    public function store(Request $request)
    {
        // Validation des champs
        $request->validate([
            'identifier' => ['required', 'string'],
            'password'   => ['required', 'string'],
        ], [
            'identifier.required' => 'L\'identifiant est obligatoire.',
            'password.required'   => 'Le mot de passe est obligatoire.',
        ]);

        // Tentative d'authentification via le service
        $result = $this->authService->attempt(
            $request->identifier,
            $request->password
        );

        // Échec
        if (! $result['success']) {
            return back()
                ->withInput($request->only('identifier'))
                ->withErrors(['identifier' => $result['message']]);
        }

        // Succès — régénère la session pour éviter la fixation de session
        $request->session()->regenerate();

        return redirect($result['redirect']);
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}