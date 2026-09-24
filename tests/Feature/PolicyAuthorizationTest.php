<?php

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\Faculty;
use App\Models\Grade;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;

test('only an administrator may create and delete students', function () {
    $admin = User::factory()->admin()->create();
    $instructor = Instructor::factory()->create();
    $student = Student::factory()->create();

    expect($admin->can('create', Student::class))->toBeTrue()
        ->and($admin->can('delete', $student))->toBeTrue()
        ->and($instructor->user->can('create', Student::class))->toBeFalse()
        ->and($instructor->user->can('delete', $student))->toBeFalse()
        ->and($student->user->can('delete', $student))->toBeFalse();
});

test('an instructor may only view students enrolled in a course they teach', function () {
    $instructor = Instructor::factory()->create();
    $ownCourse = Course::factory()->for($instructor)->create();

    $enrolled = Student::factory()->create();
    Enrollment::factory()->for($enrolled)->for($ownCourse)->create();

    $stranger = Student::factory()->create();

    expect($instructor->user->can('view', $enrolled))->toBeTrue()
        ->and($instructor->user->can('view', $stranger))->toBeFalse();
});

test('a student may only view their own record', function () {
    $student = Student::factory()->create();
    $other = Student::factory()->create();

    expect($student->user->can('view', $student))->toBeTrue()
        ->and($student->user->can('view', $other))->toBeFalse();
});

test('a student may maintain their own profile but not the academic fields', function () {
    $student = Student::factory()->create();
    $other = Student::factory()->create();

    expect($student->user->can('updateProfile', $student))->toBeTrue()
        ->and($student->user->can('update', $student))->toBeFalse()
        ->and($student->user->can('updateProfile', $other))->toBeFalse();
});

test('an instructor may only grade enrollments in a course they teach', function () {
    $instructor = Instructor::factory()->create();
    $ownCourse = Course::factory()->for($instructor)->create();
    $otherCourse = Course::factory()->create();

    $ownEnrollment = Enrollment::factory()->for($ownCourse)->create();
    $otherEnrollment = Enrollment::factory()->for($otherCourse)->create();

    expect($instructor->user->can('create', [Grade::class, $ownEnrollment]))->toBeTrue()
        ->and($instructor->user->can('create', [Grade::class, $otherEnrollment]))->toBeFalse();
});

test('a student may read their own grade but never change it', function () {
    $enrollment = Enrollment::factory()->create();
    $grade = Grade::factory()->for($enrollment)->create();

    $owner = $enrollment->student->user;
    $other = Student::factory()->create()->user;

    expect($owner->can('view', $grade))->toBeTrue()
        ->and($owner->can('update', $grade))->toBeFalse()
        ->and($other->can('view', $grade))->toBeFalse()
        ->and($other->can('update', $grade))->toBeFalse();
});

test('an instructor may only record attendance for a course they teach', function () {
    $instructor = Instructor::factory()->create();
    $ownCourse = Course::factory()->for($instructor)->create();
    $otherCourse = Course::factory()->create();

    $ownAttendance = Attendance::factory()->for($ownCourse)->create();
    $otherAttendance = Attendance::factory()->for($otherCourse)->create();

    expect($instructor->user->can('create', [Attendance::class, $ownCourse]))->toBeTrue()
        ->and($instructor->user->can('create', [Attendance::class, $otherCourse]))->toBeFalse()
        ->and($instructor->user->can('update', $ownAttendance))->toBeTrue()
        ->and($instructor->user->can('update', $otherAttendance))->toBeFalse();
});

test('an instructor may not manage user accounts', function () {
    $instructor = Instructor::factory()->create();
    $account = User::factory()->create();

    expect($instructor->user->can('viewAny', User::class))->toBeFalse()
        ->and($instructor->user->can('create', User::class))->toBeFalse()
        ->and($instructor->user->can('update', $account))->toBeFalse()
        ->and($instructor->user->can('delete', $account))->toBeFalse();
});

test('a faculty that still has departments cannot be deleted', function () {
    $admin = User::factory()->admin()->create();

    $withDepartments = Faculty::factory()->create();
    Department::factory()->for($withDepartments)->create();

    $empty = Faculty::factory()->create();

    expect($admin->can('delete', $withDepartments))->toBeFalse()
        ->and($admin->can('delete', $empty))->toBeTrue();
});

test('a department that still has related records cannot be deleted', function () {
    $admin = User::factory()->admin()->create();

    $withStudents = Department::factory()->create();
    Student::factory()->for($withStudents)->create();

    $empty = Department::factory()->create();

    expect($admin->can('delete', $withStudents))->toBeFalse()
        ->and($admin->can('delete', $empty))->toBeTrue();
});
