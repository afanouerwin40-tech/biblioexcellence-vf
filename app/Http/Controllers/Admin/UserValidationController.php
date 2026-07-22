<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AccountApprovedNotification;
use App\Notifications\AccountRejectedNotification;

class UserValidationController extends Controller
{
    public function index()
    {
        // Correction : student (singulier) et teacher (singulier)
        $pending = User::with(['student.department.faculty', 'teacher.department.faculty'])
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

    public function show(User $user)
    {
        // Correction ici aussi
        $user->load(['student.department.faculty', 'teacher.department.faculty']);
        return view('admin.validations.show', compact('user'));
    }

    public function approve(User $user)
    {
        $oldStatus = $user->status;
        $user->update(['status' => 'approved', 'rejection_reason' => null]);
        $this->log('account_approved', $user, ['status' => $oldStatus], ['status' => 'approved']);
        if ($user->email) {
            $user->notify(new AccountApprovedNotification());
        }
        return back()->with('success', "Le compte de {$user->name} a été approuvé.");
    }

    public function reject(Request $request, User $user)
    {
        $request->validate(['raison' => ['nullable', 'string', 'max:500']]);
        $oldStatus = $user->status;
        $user->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->raison ?: null,
        ]);
        $this->log('account_rejected', $user, ['status' => $oldStatus], ['status' => 'rejected']);
        if ($user->email) {
            $user->notify(new AccountRejectedNotification($request->raison ?? ''));
        }
        return back()->with('success', "Le compte de {$user->name} a été rejeté.");
    }

    public function suspend(User $user)
    {
        $oldStatus = $user->status;
        $user->update(['status' => 'suspended']);
        $this->log('account_suspended', $user, ['status' => $oldStatus], ['status' => 'suspended']);
        return back()->with('success', "Le compte de {$user->name} a été suspendu.");
    }

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