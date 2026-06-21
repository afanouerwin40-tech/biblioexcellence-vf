<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserValidationController extends Controller
{
    /**
     * Liste tous les comptes en attente de validation.
     */
    public function index()
    {
        $pending = User::with(['students.department.faculty', 'teachers.department.faculty'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        $stats = [
            'pending'   => User::where('status', 'pending')->count(),
            'approved'  => User::where('status', 'approved')->count(),
            'rejected'  => User::where('status', 'rejected')->count(),
            'suspended' => User::where('status', 'suspended')->count(),
        ];

        return view('admin.validations.index', compact('pending', 'stats'));
    }

    /**
     * Affiche le détail d'un compte en attente.
     */
    public function show(User $user)
    {
        $user->load(['students.department.faculty', 'teachers.department.faculty', 'roles']);

        return view('admin.validations.show', compact('user'));
    }

    /**
     * Approuve un compte.
     */
    public function approve(User $user)
    {
        $oldStatus = $user->status;

        $user->update(['status' => 'approved']);

        // Enregistrement dans audit_logs
        $this->log('account_approved', $user, ['status' => $oldStatus], ['status' => 'approved']);

        return back()->with('success', "Le compte de {$user->name} a été approuvé.");
    }

    /**
     * Rejette un compte.
     */
    public function reject(Request $request, User $user)
    {
        $request->validate([
            'raison' => ['nullable', 'string', 'max:500'],
        ]);

        $oldStatus = $user->status;

        $user->update(['status' => 'rejected']);

        $this->log('account_rejected', $user, ['status' => $oldStatus], ['status' => 'rejected']);

        return back()->with('success', "Le compte de {$user->name} a été rejeté.");
    }

    /**
     * Suspend un compte.
     */
    public function suspend(User $user)
    {
        $oldStatus = $user->status;

        $user->update(['status' => 'suspended']);

        $this->log('account_suspended', $user, ['status' => $oldStatus], ['status' => 'suspended']);

        return back()->with('success', "Le compte de {$user->name} a été suspendu.");
    }

    /**
     * Enregistre l'action dans audit_logs.
     */
    private function log(string $action, User $target, array $old, array $new): void
    {
        AuditLog::create([
            'user_id'    => Auth::id(),
            'action'     => $action,
            'model_type' => User::class,
            'model_id'   => $target->id,
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}