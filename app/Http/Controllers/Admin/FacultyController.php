<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFacultyRequest;
use App\Http\Requests\UpdateFacultyRequest;
use App\Models\Faculty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class FacultyController extends Controller
{
    /**
     * Display a searchable, paginated list of faculties.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Faculty::class);

        $search = $request->string('search')->trim()->value();

        return Inertia::render('admin/faculties/index', [
            'faculties' => Faculty::query()
                ->search($search)
                ->withCount(['departments', 'students', 'courses'])
                ->orderBy('name')
                ->paginate(10)
                ->withQueryString(),
            'filters' => ['search' => $search],
        ]);
    }

    /**
     * Show the form for creating a new faculty.
     */
    public function create(): Response
    {
        Gate::authorize('create', Faculty::class);

        return Inertia::render('admin/faculties/create');
    }

    /**
     * Store a newly created faculty.
     */
    public function store(StoreFacultyRequest $request): RedirectResponse
    {
        Gate::authorize('create', Faculty::class);

        $faculty = Faculty::query()->create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Faculty created successfully.')]);

        return to_route('admin.faculties.show', $faculty);
    }

    /**
     * Display the faculty together with its departments.
     */
    public function show(Faculty $faculty): Response
    {
        Gate::authorize('view', $faculty);

        return Inertia::render('admin/faculties/show', [
            'faculty' => $faculty->only(['id', 'name', 'description']),
            'departments' => $faculty->departments()
                ->withCount(['students', 'courses', 'instructors'])
                ->orderBy('name')
                ->get(['id', 'faculty_id', 'name', 'description']),
        ]);
    }

    /**
     * Show the form for editing the faculty.
     */
    public function edit(Faculty $faculty): Response
    {
        Gate::authorize('update', $faculty);

        return Inertia::render('admin/faculties/edit', [
            'faculty' => $faculty->only(['id', 'name', 'description']),
        ]);
    }

    /**
     * Update the faculty.
     */
    public function update(UpdateFacultyRequest $request, Faculty $faculty): RedirectResponse
    {
        Gate::authorize('update', $faculty);

        $faculty->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Faculty updated successfully.')]);

        return to_route('admin.faculties.show', $faculty);
    }

    /**
     * Remove the faculty when it has no departments left.
     */
    public function destroy(Faculty $faculty): RedirectResponse
    {
        Gate::authorize('delete', $faculty);

        $faculty->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Faculty deleted successfully.')]);

        return to_route('admin.faculties.index');
    }
}
