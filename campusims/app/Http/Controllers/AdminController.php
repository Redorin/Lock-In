<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CampusSpace;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends \Illuminate\Routing\Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->isAdmin()) {
                abort(403, 'Unauthorized.');
            }
            return $next($request);
        });
    }

    // ══ Dashboard ═════════════════════════════════════════════════════════════

    public function dashboard()
    {
        $stats = [
            'total_spaces'      => CampusSpace::count(),
            'total_users'       => User::where('role', 'student')->where('status', 'approved')->count(),
            'total_capacity'    => CampusSpace::sum('capacity'),
            'current_occupancy' => CampusSpace::sum('current_occupancy'),
            'avg_occupancy'     => CampusSpace::count()
                ? round((CampusSpace::sum('current_occupancy') / max(CampusSpace::sum('capacity'), 1)) * 100) : 0,
            'pending_count'     => User::where('status', 'pending')->count(),
        ];
        $spaces = CampusSpace::orderBy('building')->orderBy('name')->get();
        return view('admin.dashboard', compact('stats', 'spaces'));
    }

    // ══ Spaces ════════════════════════════════════════════════════════════════

    public function spaces(Request $request)
    {
        $query = CampusSpace::query();
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('building', 'like', "%{$s}%");
            });
        }
        if ($request->filled('building')) {
            $query->where('building', $request->building);
        }
        $spaces   = $query->orderBy('building')->orderBy('name')->get();
        $buildings = CampusSpace::distinct()->pluck('building');
        return view('admin.spaces', compact('spaces', 'buildings'));
    }

    public function storeSpace(Request $request)
    {
        $data = $request->validate([
            'building' => ['required', 'string', 'max:255'],
            'name'     => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:1'],
        ]);
        $space = CampusSpace::create([...$data, 'current_occupancy' => 0]);
        ActivityLog::log('added Space', "Added: {$space->building} - {$space->name} (cap: {$space->capacity})", $space->name);
        return back()->with('success', "Space \"{$space->building} - {$space->name}\" added.");
    }

    public function updateSpace(Request $request, CampusSpace $space)
    {
        $data = $request->validate([
            'capacity'          => ['required', 'integer', 'min:1'],
            'current_occupancy' => ['required', 'integer', 'min:0'],
        ]);
        $old = $space->capacity;
        $space->update($data);
        ActivityLog::log('updated Space', "Updated {$space->building} - {$space->name}. Cap: {$old}→{$data['capacity']}, Occ: {$data['current_occupancy']}", $space->name);
        return back()->with('success', "Space \"{$space->name}\" updated.");
    }

    public function destroySpace(CampusSpace $space)
    {
        $label = "{$space->building} - {$space->name}";
        $space->delete();
        ActivityLog::log('deleted Space', "Deleted space: {$label}", $label);
        return back()->with('success', "Space \"{$label}\" deleted.");
    }

    // ══ Users ═════════════════════════════════════════════════════════════════

    public function users(Request $request)
    {
        $query = User::where('role', 'student')->where('status', 'approved');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('student_id', 'like', "%{$s}%");
            });
        }
        if ($request->filled('status')) {
            $active = $request->status === 'active' ? true : false;
            $query->where('is_active', $active);
        }

        $users = $query->orderBy('name')->get();
        return view('admin.users', compact('users'));
    }

    public function toggleActive(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();
        $action = $user->is_active ? 'activated' : 'deactivated';
        ActivityLog::log("{$action} User", ucfirst($action) . " user: {$user->name} ({$user->email})", $user->name);
        return back()->with('success', "User \"{$user->name}\" {$action}.");
    }

    public function destroyUser(User $user)
    {
        $name = $user->name; $email = $user->email;
        $user->delete();
        ActivityLog::log('deleted User', "Deleted user: {$name} ({$email})", $name);
        return back()->with('success', "User \"{$name}\" deleted.");
    }

    // ══ Verifications ═════════════════════════════════════════════════════════

    public function verifications()
    {
        $pending  = User::where('role', 'student')->where('status', 'pending')->latest()->get();
        $rejected = User::where('role', 'student')->where('status', 'rejected')->latest()->get();
        return view('admin.verifications', compact('pending', 'rejected'));
    }

    public function approveUser(User $user)
    {
        $user->update(['status' => 'approved', 'is_active' => true]);
        ActivityLog::log('approved User', "Approved: {$user->name} ({$user->email}) ID: {$user->student_id}", $user->name);
        return back()->with('success', "Account for \"{$user->name}\" approved.");
    }

    public function rejectUser(Request $request, User $user)
    {
        $data = $request->validate(['rejection_reason' => ['required', 'string', 'max:500']]);
        $user->update(['status' => 'rejected', 'rejection_reason' => $data['rejection_reason']]);
        ActivityLog::log('rejected User', "Rejected: {$user->name}. Reason: {$data['rejection_reason']}", $user->name);
        return back()->with('success', "Account for \"{$user->name}\" rejected.");
    }

    // ══ Admins ════════════════════════════════════════════════════════════════

    public function admins()
    {
        $admins = User::where('role', 'admin')->orderBy('name')->get();
        return view('admin.admins', compact('admins'));
    }

    public function storeAdmin(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $admin = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role'      => 'admin',
            'status'    => 'approved',
            'is_active' => true,
        ]);

        ActivityLog::log('created Admin', "Created new admin account: {$admin->name} ({$admin->email})", $admin->name);
        return back()->with('success', "Admin account for \"{$admin->name}\" created.");
    }

    public function destroyAdmin(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own admin account.');
        }
        $name = $user->name;
        $user->delete();
        ActivityLog::log('deleted Admin', "Deleted admin account: {$name}", $name);
        return back()->with('success', "Admin \"{$name}\" deleted.");
    }

    // ══ Activity Logs ══════════════════════════════════════════════════════════

    public function activityLogs()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(30);
        return view('admin.activity-logs', compact('logs'));
    }
}