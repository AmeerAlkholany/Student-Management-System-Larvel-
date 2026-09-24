<?php

use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('an administrator is redirected to the admin dashboard', function () {
    $this->actingAs(User::factory()->admin()->create());

    $this->get(route('dashboard'))->assertRedirect(route('admin.dashboard'));
});

test('an instructor is redirected to the instructor dashboard', function () {
    $instructor = Instructor::factory()->create();

    $this->actingAs($instructor->user);

    $this->get(route('dashboard'))->assertRedirect(route('instructor.dashboard'));
});

test('a student is redirected to the student dashboard', function () {
    $student = Student::factory()->create();

    $this->actingAs($student->user);

    $this->get(route('dashboard'))->assertRedirect(route('student.dashboard'));
});

test('each role can open its own dashboard', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.dashboard'))
        ->assertOk();

    $this->actingAs(Instructor::factory()->create()->user)
        ->get(route('instructor.dashboard'))
        ->assertOk();

    $this->actingAs(Student::factory()->create()->user)
        ->get(route('student.dashboard'))
        ->assertOk();
});

test('a user without a linked profile cannot open the role dashboard', function () {
    $this->actingAs(User::factory()->instructor()->create())
        ->get(route('instructor.dashboard'))
        ->assertForbidden();

    $this->actingAs(User::factory()->student()->create())
        ->get(route('student.dashboard'))
        ->assertForbidden();
});
