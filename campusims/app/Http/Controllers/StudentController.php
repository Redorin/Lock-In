<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends \Illuminate\Routing\Controller
{
    // ── List all students ────────────────────────────────────────────────────
    public function index()
    {
        $students = Student::latest()->paginate(20);
        return view('students.index', compact('students'));
    }

    // ── Show create form ─────────────────────────────────────────────────────
    public function create()
    {
        return view('students.create');
    }

    // ── Store new student ────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'student_number' => ['required', 'string', 'unique:students,student_number'],
            'first_name'     => ['required', 'string', 'max:255'],
            'last_name'      => ['required', 'string', 'max:255'],
            'middle_name'    => ['nullable', 'string', 'max:255'],
            'birth_date'     => ['nullable', 'date'],
            'gender'         => ['nullable', 'in:male,female,other'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'address'        => ['nullable', 'string'],
            'course'         => ['nullable', 'string', 'max:255'],
            'year_level'     => ['nullable', 'string', 'max:10'],
            'section'        => ['nullable', 'string', 'max:50'],
            'status'         => ['nullable', 'in:active,inactive,graduated,dropped'],
        ]);

        Student::create($data);

        return redirect()->route('students.index')
                         ->with('success', 'Student record created successfully.');
    }

    // ── Show single student ──────────────────────────────────────────────────
    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    // ── Show edit form ───────────────────────────────────────────────────────
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    // ── Update student ───────────────────────────────────────────────────────
    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'student_number' => ['required', 'string', 'unique:students,student_number,' . $student->id],
            'first_name'     => ['required', 'string', 'max:255'],
            'last_name'      => ['required', 'string', 'max:255'],
            'middle_name'    => ['nullable', 'string', 'max:255'],
            'birth_date'     => ['nullable', 'date'],
            'gender'         => ['nullable', 'in:male,female,other'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'address'        => ['nullable', 'string'],
            'course'         => ['nullable', 'string', 'max:255'],
            'year_level'     => ['nullable', 'string', 'max:10'],
            'section'        => ['nullable', 'string', 'max:50'],
            'status'         => ['nullable', 'in:active,inactive,graduated,dropped'],
        ]);

        $student->update($data);

        return redirect()->route('students.show', $student)
                         ->with('success', 'Student record updated.');
    }

    // ── Delete student ───────────────────────────────────────────────────────
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')
                         ->with('success', 'Student record deleted.');
    }
}