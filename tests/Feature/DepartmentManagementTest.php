<?php

use App\Models\Department;
use App\Models\Faculty;
use App\Models\Student;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('an administrator sees the department list with its faculty', function () {
    $faculty = Faculty::factory()->create(['name' => 'Faculty of Science']);
    Department::factory()->for($faculty)->create(['name' => 'Computer Science']);

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.departments.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/departments/index')
            ->where('departments.data.0.name', 'Computer Science')
            ->where('departments.data.0.faculty.name', 'Faculty of Science'),
        );
});

test('the department list can be filtered by faculty through the query string', function () {
    $science = Faculty::factory()->create();
    Department::factory()->for($science)->create(['name' => 'Physics']);
    Department::factory()->create(['name' => 'Accounting']);

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.departments.index', ['faculty' => $science->id]))
        ->assertInertia(fn (Assert $page) => $page
            ->where('filters.faculty', $science->id)
            ->count('departments.data', 1)
            ->where('departments.data.0.name', 'Physics'),
        );
});

test('an administrator can create a department', function () {
    $faculty = Faculty::factory()->create();

    $this->actingAs(User::factory()->admin()->create())
        ->post(route('admin.departments.store'), [
            'faculty_id' => $faculty->id,
            'name' => 'Mathematics',
            'description' => 'Pure and applied mathematics.',
        ])
        ->assertRedirect(route('admin.departments.show', Department::query()->sole()));

    $this->assertDatabaseHas('departments', [
        'faculty_id' => $faculty->id,
        'name' => 'Mathematics',
    ]);
});

test('a department needs a faculty that exists', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->post(route('admin.departments.store'), [
            'faculty_id' => 9999,
            'name' => 'Mathematics',
        ])
        ->assertSessionHasErrors('faculty_id');

    $this->assertDatabaseCount('departments', 0);
});

test('a department name is unique within its faculty but free across faculties', function () {
    $science = Faculty::factory()->create();
    $engineering = Faculty::factory()->create();
    Department::factory()->for($science)->create(['name' => 'Mathematics']);

    $this->actingAs(User::factory()->admin()->create());

    $this->post(route('admin.departments.store'), [
        'faculty_id' => $science->id,
        'name' => 'Mathematics',
    ])->assertSessionHasErrors('name');

    $this->post(route('admin.departments.store'), [
        'faculty_id' => $engineering->id,
        'name' => 'Mathematics',
    ])->assertSessionHasNoErrors();

    $this->assertDatabaseCount('departments', 2);
});

test('a department keeps its own name while being updated', function () {
    $department = Department::factory()->create(['name' => 'Mathematics']);

    $this->actingAs(User::factory()->admin()->create())
        ->put(route('admin.departments.update', $department), [
            'faculty_id' => $department->faculty_id,
            'name' => 'Mathematics',
            'description' => 'Updated description.',
        ])
        ->assertRedirect(route('admin.departments.show', $department))
        ->assertSessionHasNoErrors();

    expect($department->refresh()->description)->toBe('Updated description.');
});

test('an empty department can be deleted', function () {
    $department = Department::factory()->create();

    $this->actingAs(User::factory()->admin()->create())
        ->delete(route('admin.departments.destroy', $department))
        ->assertRedirect(route('admin.departments.index'));

    $this->assertDatabaseMissing('departments', ['id' => $department->id]);
});

test('a department with students cannot be deleted', function () {
    $department = Department::factory()->create();
    Student::factory()->for($department)->create();

    $this->actingAs(User::factory()->admin()->create())
        ->delete(route('admin.departments.destroy', $department))
        ->assertForbidden();

    $this->assertDatabaseHas('departments', ['id' => $department->id]);
});

test('a student cannot reach department management', function () {
    $department = Department::factory()->create();

    $this->actingAs(Student::factory()->create()->user);

    $this->get(route('admin.departments.index'))->assertForbidden();
    $this->post(route('admin.departments.store'), [
        'faculty_id' => $department->faculty_id,
        'name' => 'Mathematics',
    ])->assertForbidden();
    $this->delete(route('admin.departments.destroy', $department))->assertForbidden();

    $this->assertDatabaseMissing('departments', ['name' => 'Mathematics']);
    $this->assertDatabaseHas('departments', ['id' => $department->id]);
});
