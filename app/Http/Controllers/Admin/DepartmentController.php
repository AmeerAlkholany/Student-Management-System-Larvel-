<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class DepartmentController extends Controller
{
    /**
     * Display a searchable, paginated list of departments.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Department::class);

        $filters = [
            'search' => $request->string('search')->trim()->value(),
            'faculty' => $request->integer('faculty') ?: null,
        ];

        return Inertia::render('admin/departments/index', [
            'departments' => Department::query()
                ->search($filters['search'])
                ->when(
                    $filters['faculty'],
                    fn (Builder $query, int $faculty) => $query->where('faculty_id', $faculty),
                )
                ->with('faculty:id,name')
                ->withCount(['students', 'courses', 'instructors'])
                ->orderBy('name')
                ->paginate(10)
                ->withQueryString(),
            'faculties' => $this->facultyOptions(),
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new department.
     */
    public function create(): Response
    {
        Gate::authorize('create', Department::class);

        return Inertia::render('admin/departments/create', [
            'faculties' => $this->facultyOptions(),
        ]);
    }

    /**
     * Store a newly created department.
     */
    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        Gate::authorize('create', Department::class);

        $department = Department::query()->create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Department created successfully.')]);

        return to_route('admin.departments.show', $department);
    }

    /**
     * Display the department with its students, courses and instructors.
     */
    public function show(Department $department): Response
    {
        Gate::authorize('view', $department);

        $department->load('faculty:id,name');

        return Inertia::render('admin/departments/show', [
            'department' => [
                ...$department->only(['id', 'faculty_id', 'name', 'description']),
                'faculty' => $department->faculty->only(['id', 'name']),
            ],
            'students' => $department->students()
                ->orderBy('student_number')
                ->get(['id', 'student_number', 'first_name', 'last_name', 'status']),
            'courses' => $department->courses()
                ->with(['instructor:id,user_id', 'instructor.user:id,name'])
                ->orderBy('code')
                ->get(['id', 'department_id', 'instructor_id', 'code', 'name', 'credit_hours']),
            'instructors' => $department->instructors()
                ->with('user:id,name')
                ->orderBy('employee_number')
                ->get(['id', 'user_id', 'employee_number', 'specialization']),
        ]);
    }

    /**
     * Show the form for editing the department.
     */
    public function edit(Department $department): Response
    {
        Gate::authorize('update', $department);

        return Inertia::render('admin/departments/edit', [
            'department' => $department->only(['id', 'faculty_id', 'name', 'description']),
            'faculties' => $this->facultyOptions(),
        ]);
    }

    /**
     * Update the department.
     */
    public function update(UpdateDepartmentRequest $request, Department $department): RedirectResponse
    {
        Gate::authorize('update', $department);

        $department->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Department updated successfully.')]);

        return to_route('admin.departments.show', $department);
    }

    /**
     * Remove the department when nothing depends on it.
     */
    public function destroy(Department $department): RedirectResponse
    {
        Gate::authorize('delete', $department);

        $department->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Department deleted successfully.')]);

        return to_route('admin.departments.index');
    }

    /**
     * Get the faculties offered by the faculty select inputs.
     *
     * @return Collection<int, Faculty>
     */
    private function facultyOptions(): Collection
    {
        return Faculty::query()->orderBy('name')->get(['id', 'name']);
    }
}
