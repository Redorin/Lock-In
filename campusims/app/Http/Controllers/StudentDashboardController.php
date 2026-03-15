<?php

namespace App\Http\Controllers;

use App\Models\CampusSpace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentDashboardController extends \Illuminate\Routing\Controller
{
    public function dashboard()
    {
        $spaces  = CampusSpace::where('is_active', true)->orderBy('building')->orderBy('name')->get();
        $grouped = $spaces->groupBy('building');
        return view('student.dashboard', compact('spaces', 'grouped'));
    }

    public function map()
    {
        return view('student.map');
    }

    public function idCard()
    {
        return view('student.id-card', ['user' => Auth::user()]);
    }

    public function settings()
    {
        return view('student.settings', ['user' => Auth::user()]);
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'email', 'unique:users,email,' . $user->id],
            'current_password' => ['nullable', 'string'],
            'password'         => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $user->password = Hash::make($data['password']);
        }

        $user->name  = $data['name'];
        $user->email = $data['email'];
        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}