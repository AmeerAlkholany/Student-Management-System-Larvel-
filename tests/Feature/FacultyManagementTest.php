<?php

use App\Models\Department;
use App\Models\Faculty;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('an administrator sees the faculty list', function () {
    $faculty = Faculty::factory()->create(['name' => 'Faculty of Engineering']);
    Department::factory()->for($faculty)->create();

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.faculties.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/faculties/index')
            ->where('faculties.data.0.name', 'Faculty of Engineering')
            ->where('faculties.data.0.departments_count', 1),
        );
});

test('the faculty list can be searched through the query string', function () {
    Faculty::factory()->create(['name' => 'Faculty of Science']);
    Faculty::factory()->create(['name' => 'Faculty of Business']);

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.faculties.index', ['search' => 'Science']))
        ->assertInertia(fn (Assert $page) => $page
            ->where('filters.search', 'Science')
            ->count('faculties.data', 1)
            ->where('faculties.data.0.name', 'Faculty of Science'),
        );
});

test('an administrator can create a faculty', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->post(route('admin.faculties.store'), [
            'name' => 'Faculty of Law',
            'description' => 'Legal studies.',
        ])
        ->assertRedirect(route('admin.faculties.show', Faculty::query()->sole()));

    $this->assertDatabaseHas('faculties', [
        'name' => 'Faculty of Law',
        'description' => 'Legal studies.',
    ]);
});

test('a faculty name must be unique', function () {
    Faculty::factory()->create(['name' => 'Faculty of Science']);

    $this->actingAs(User::factory()->admin()->create())
        ->post(route('admin.faculties.store'), ['name' => 'Faculty of Science'])
        ->assertSessionHasErrors('name');

    expect(Faculty::query()->count())->toBe(1);
});

test('a faculty keeps its own name while being updated', function () {
    $faculty = Faculty::factory()->create(['name' => 'Faculty of Science']);

    $this->actingAs(User::factory()->admin()->create())
        ->put(route('admin.faculties.update', $faculty), [
            'name' => 'Faculty of Science',
            'description' => 'Updated description.',
        ])
        ->assertRedirect(route('admin.faculties.show', $faculty))
        ->assertSessionHasNoErrors();

    expect($faculty->refresh()->description)->toBe('Updated description.');
});

test('a faculty cannot take the name of another faculty', function () {
    Faculty::factory()->create(['name' => 'Faculty of Science']);
    $faculty = Faculty::factory()->create(['name' => 'Faculty of Business']);

    $this->actingAs(User::factory()->admin()->create())
        ->put(route('admin.faculties.update', $faculty), ['name' => 'Faculty of Science'])
        ->assertSessionHasErrors('name');

    expect($faculty->refresh()->name)->toBe('Faculty of Business');
});

test('an empty faculty can be deleted', function () {
    $faculty = Faculty::factory()->create();

    $this->actingAs(User::factory()->admin()->create())
        ->delete(route('admin.faculties.destroy', $faculty))
        ->assertRedirect(route('admin.faculties.index'));

    $this->assertDatabaseMissing('faculties', ['id' => $faculty->id]);
});

test('a faculty with departments cannot be deleted', function () {
    $faculty = Faculty::factory()->create();
    Department::factory()->for($faculty)->create();

    $this->actingAs(User::factory()->admin()->create())
        ->delete(route('admin.faculties.destroy', $faculty))
        ->assertForbidden();

    $this->assertDatabaseHas('faculties', ['id' => $faculty->id]);
});

test('only an administrator may reach faculty management', function () {
    $faculty = Faculty::factory()->create();

    foreach ([Student::factory()->create()->user, Instructor::factory()->create()->user] as $user) {
        $this->actingAs($user);

        $this->get(route('admin.faculties.index'))->assertForbidden();
        $this->get(route('admin.faculties.create'))->assertForbidden();
        $this->post(route('admin.faculties.store'), ['name' => 'Faculty of Law'])->assertForbidden();
        $this->put(route('admin.faculties.update', $faculty), ['name' => 'Renamed'])->assertForbidden();
        $this->delete(route('admin.faculties.destroy', $faculty))->assertForbidden();
    }

    $this->assertDatabaseMissing('faculties', ['name' => 'Faculty of Law']);
    $this->assertDatabaseHas('faculties', ['id' => $faculty->id, 'name' => $faculty->name]);
});

test('a guest is sent to login before faculty management', function () {
    $this->get(route('admin.faculties.index'))->assertRedirect(route('login'));
});
