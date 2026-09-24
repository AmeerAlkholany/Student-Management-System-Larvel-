<?php

use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;

test('guests are sent to login for every role area', function (string $route) {
    $this->get(route($route))->assertRedirect(route('login'));
})->with(['admin.dashboard', 'instructor.dashboard', 'student.dashboard']);

test('a student cannot reach admin or instructor routes', function () {
    $this->actingAs(Student::factory()->create()->user);

    $this->get(route('admin.dashboard'))->assertForbidden();
    $this->get(route('instructor.dashboard'))->assertForbidden();
});

test('an instructor cannot reach admin or student routes', function () {
    $this->actingAs(Instructor::factory()->create()->user);

    $this->get(route('admin.dashboard'))->assertForbidden();
    $this->get(route('student.dashboard'))->assertForbidden();
});

test('an administrator cannot reach instructor or student routes', function () {
    $this->actingAs(User::factory()->admin()->create());

    $this->get(route('instructor.dashboard'))->assertForbidden();
    $this->get(route('student.dashboard'))->assertForbidden();
});
