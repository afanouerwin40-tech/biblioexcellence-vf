<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountStatus 
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $status = Auth::user()->status;

            if ($status !== 'approved') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect('/login')->withErrors([
                    'identifier' => $this->getStatusMessage($status),
                ]);
            }
        }

        return $next($request);
    }

    private function getStatusMessage(string $status): string
    {
        return match ($status) {
            'pending'   => 'Votre compte est en attente de validation.',
            'rejected'  => 'Votre compte a été refusé. Contactez l\'administration.',
            'suspended' => 'Votre compte a été suspendu. Contactez l\'administration.',
            default     => 'Accès non autorisé.',
        };
    }
}