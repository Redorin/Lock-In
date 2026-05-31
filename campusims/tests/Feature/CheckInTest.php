<?php

use App\Models\User;
use App\Models\CampusSpace;
use App\Models\CheckIn;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected from check-in page and scan route', function () {
    $space = CampusSpace::create([
        'building' => 'L Building',
        'name' => 'Kwago',
        'capacity' => 5,
        'current_occupancy' => 0,
    ]);

    $this->get(route('student.scanner'))->assertRedirect(route('login'));
    $this->get(route('checkin.scan', ['space' => $space->id, 'token' => $space->qrToken()]))->assertRedirect(route('login'));
});

test('approved student can scan a valid QR code to check in and increase occupancy', function () {
    $student = User::create([
        'name' => 'Test Student',
        'email' => 'student@campus.edu',
        'password' => bcrypt('password'),
        'role' => 'student',
        'status' => 'approved',
        'is_active' => true,
    ]);

    $space = CampusSpace::create([
        'building' => 'L Building',
        'name' => 'Kwago',
        'capacity' => 5,
        'current_occupancy' => 0,
    ]);

    $response = $this->actingAs($student)->get(route('checkin.scan', [
        'space' => $space->id,
        'token' => $space->qrToken(),
    ]));

    $response->assertRedirect(route('student.checked-in'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('check_ins', [
        'user_id' => $student->id,
        'active_user_id' => $student->id,
        'campus_space_id' => $space->id,
        'checked_out_at' => null,
    ]);

    $this->assertEquals(1, $space->refresh()->current_occupancy);
});

test('student cannot check in to a full space', function () {
    $student = User::create([
        'name' => 'Test Student',
        'email' => 'student@campus.edu',
        'password' => bcrypt('password'),
        'role' => 'student',
        'status' => 'approved',
        'is_active' => true,
    ]);

    $space = CampusSpace::create([
        'building' => 'L Building',
        'name' => 'Kwago',
        'capacity' => 1,
        'current_occupancy' => 1,
    ]);

    $response = $this->actingAs($student)->get(route('checkin.scan', [
        'space' => $space->id,
        'token' => $space->qrToken(),
    ]));

    $response->assertViewIs('student.checkin-result');
    $response->assertViewHas('success', false);
    $response->assertViewHas('message', "{$space->building} — {$space->name} is currently full ({$space->capacity}/{$space->capacity}).");

    $this->assertDatabaseMissing('check_ins', [
        'user_id' => $student->id,
    ]);

    $this->assertEquals(1, $space->refresh()->current_occupancy);
});

test('student cannot check in to the same space twice', function () {
    $student = User::create([
        'name' => 'Test Student',
        'email' => 'student@campus.edu',
        'password' => bcrypt('password'),
        'role' => 'student',
        'status' => 'approved',
        'is_active' => true,
    ]);

    $space = CampusSpace::create([
        'building' => 'L Building',
        'name' => 'Kwago',
        'capacity' => 5,
        'current_occupancy' => 0,
    ]);

    // First check-in
    $this->actingAs($student)->get(route('checkin.scan', [
        'space' => $space->id,
        'token' => $space->qrToken(),
    ]));

    $this->assertEquals(1, $space->refresh()->current_occupancy);

    // Second check-in
    $response = $this->actingAs($student)->get(route('checkin.scan', [
        'space' => $space->id,
        'token' => $space->qrToken(),
    ]));

    $response->assertViewIs('student.checkin-result');
    $response->assertViewHas('success', false);

    $this->assertEquals(1, $space->refresh()->current_occupancy);
});

test('checking in to a new space auto checks out from the previous space and updates occupancies', function () {
    $student = User::create([
        'name' => 'Test Student',
        'email' => 'student@campus.edu',
        'password' => bcrypt('password'),
        'role' => 'student',
        'status' => 'approved',
        'is_active' => true,
    ]);

    $space1 = CampusSpace::create([
        'building' => 'L Building',
        'name' => 'Kwago',
        'capacity' => 5,
        'current_occupancy' => 0,
    ]);

    $space2 = CampusSpace::create([
        'building' => 'V Building',
        'name' => 'Library',
        'capacity' => 5,
        'current_occupancy' => 0,
    ]);

    // Check in to Space 1
    $this->actingAs($student)->get(route('checkin.scan', [
        'space' => $space1->id,
        'token' => $space1->qrToken(),
    ]));

    $this->assertEquals(1, $space1->refresh()->current_occupancy);
    $this->assertEquals(0, $space2->refresh()->current_occupancy);

    // Check in to Space 2
    $response = $this->actingAs($student)->get(route('checkin.scan', [
        'space' => $space2->id,
        'token' => $space2->qrToken(),
    ]));

    $response->assertRedirect(route('student.checked-in'));

    // Space 1 should now be 0, Space 2 should be 1
    $this->assertEquals(0, $space1->refresh()->current_occupancy);
    $this->assertEquals(1, $space2->refresh()->current_occupancy);

    // Verify first check-in is checked out
    $this->assertDatabaseHas('check_ins', [
        'user_id' => $student->id,
        'campus_space_id' => $space1->id,
        'active_user_id' => null,
    ]);
    $this->assertDatabaseMissing('check_ins', [
        'user_id' => $student->id,
        'campus_space_id' => $space1->id,
        'checked_out_at' => null,
    ]);

    // Verify second check-in is active
    $this->assertDatabaseHas('check_ins', [
        'user_id' => $student->id,
        'campus_space_id' => $space2->id,
        'active_user_id' => $student->id,
        'checked_out_at' => null,
    ]);
});

test('checking out of a space decrements occupancy', function () {
    $student = User::create([
        'name' => 'Test Student',
        'email' => 'student@campus.edu',
        'password' => bcrypt('password'),
        'role' => 'student',
        'status' => 'approved',
        'is_active' => true,
    ]);

    $space = CampusSpace::create([
        'building' => 'L Building',
        'name' => 'Kwago',
        'capacity' => 5,
        'current_occupancy' => 0,
    ]);

    // Check in
    $this->actingAs($student)->get(route('checkin.scan', [
        'space' => $space->id,
        'token' => $space->qrToken(),
    ]));

    $this->assertEquals(1, $space->refresh()->current_occupancy);

    // Check out
    $response = $this->actingAs($student)->post(route('checkin.checkout'));

    $response->assertRedirect(route('student.dashboard'));
    $response->assertSessionHas('success');

    $this->assertEquals(0, $space->refresh()->current_occupancy);
    $this->assertDatabaseMissing('check_ins', [
        'user_id' => $student->id,
        'checked_out_at' => null,
    ]);
});
