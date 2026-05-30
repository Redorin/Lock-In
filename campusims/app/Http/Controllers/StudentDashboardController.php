<?php

namespace App\Http\Controllers;

use App\Models\CampusSpace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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

    public function resubmitPage()
    {
        return view('student.resubmit', ['user' => Auth::user()]);
    }

    public function processResubmit(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'student_id' => ['required', 'string', 'regex:/^\d{2}-\d{4}-\d{3}$/', 'unique:users,student_id,' . $user->id],
            'id_image'   => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:10240'],
        ], [
            'student_id.regex'  => 'Student ID must follow the format: ##-####-### (e.g. 23-0066-858)',
            'student_id.unique' => 'This Student ID is already registered to another account.',
            'id_image.required' => 'Please upload a clear photo of your student ID.',
            'id_image.max'      => 'Image must be under 10MB.',
        ]);

        $oldImage = $user->id_image;
        $imageName = basename($request->file('id_image')->store('id_images', 'public'));

        $user->name = $data['name'];
        $user->student_id = $data['student_id'];
        $user->id_image = $imageName;
        $user->status = 'pending';
        $user->rejection_reason = null;
        $user->save();

        if ($oldImage) {
            Storage::disk('public')->delete('id_images/' . $oldImage);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Your application has been successfully updated and resubmitted for admin review. Please wait for approval.');
    }
}
