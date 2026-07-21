<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Redirige vers le tableau de bord adapté au rôle de l'utilisateur.
     */
    protected function redirectToDashboard()
    {
        $user = auth()->user();

        if ($user->role_type === 'student') {
            return route('student.dashboard');
        } elseif ($user->role_type === 'teacher') {
            return route('teacher.dashboard');
        } elseif ($user->role_type === 'admin') {
            return route('admin.dashboard');
        } elseif ($user->role_type === 'librarian') {
            return route('librarian.dashboard');
        }

        // Fallback si le rôle n'est pas reconnu
        return '/';
    }
}
