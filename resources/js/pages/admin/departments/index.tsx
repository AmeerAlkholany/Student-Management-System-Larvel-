import { Head, Link } from '@inertiajs/react';
import { Plus } from 'lucide-react';
import { DeleteResourceDialog } from '@/components/delete-resource-dialog';
import { EmptyState } from '@/components/empty-state';
import { FilterBar } from '@/components/filter-bar';
import { FilterSelect } from '@/components/filter-select';
import { PageHeader } from '@/components/page-header';
import { Pagination } from '@/components/pagination';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import adminRoutes from '@/routes/admin';
import departmentRoutes from '@/routes/admin/departments';
import type { Department, Faculty, Paginated } from '@/types';

type DepartmentRow = Department & {
    students_count: number;
    courses_count: number;
    instructors_count: number;
};

type DepartmentsIndexProps = {
    departments: Paginated<DepartmentRow>;
    faculties: Faculty[];
    filters: { search: string | null; faculty: number | null };
};

export default function DepartmentsIndex({
    departments,
    faculties,
    filters,
}: DepartmentsIndexProps) {
    const hasFilters = Boolean(filters.search) || filters.faculty !== null;

    return (
        <>
            <Head title="Departments" />

            <div className="flex flex-1 flex-col gap-6 p-4">
                <PageHeader
                    title="Departments"
                    description="Departments host the courses, instructors and students of a faculty."
                    action={
                        <Button asChild>
                            <Link href={departmentRoutes.create()}>
                                <Plus className="size-4" />
                                New department
                            </Link>
                        </Button>
                    }
                />

                <Card>
                    <CardContent className="space-y-4">
                        <FilterBar
                            action={departmentRoutes.index()}
                            search={filters.search}
                            searchPlaceholder="Department name"
                            hasFilters={hasFilters}
                        >
                            <FilterSelect
                                name="faculty"
                                label="Faculty"
                                value={filters.faculty}
                                anyLabel="All faculties"
                                options={faculties.map((faculty) => ({
                                    value: faculty.id,
                                    label: faculty.name,
                                }))}
                            />
                        </FilterBar>

                        {departments.data.length === 0 ? (
                            <EmptyState
                                title="No departments found"
                                description="Adjust the filters, or create a department for one of the faculties."
                            />
                        ) : (
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Name</TableHead>
                                        <TableHead>Faculty</TableHead>
                                        <TableHead>Students</TableHead>
                                        <TableHead>Courses</TableHead>
                                        <TableHead>Instructors</TableHead>
                                        <TableHead className="text-right">
                                            Actions
                                        </TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {departments.data.map((department) => {
                                        const isEmpty =
                                            department.students_count === 0 &&
                                            department.courses_count === 0 &&
                                            department.instructors_count === 0;

                                        return (
                                            <TableRow key={department.id}>
                                                <TableCell className="font-medium">
                                                    <Link
                                                        href={departmentRoutes.show(
                                                            department.id,
                                                        )}
                                                        className="underline-offset-4 hover:underline"
                                                    >
                                                        {department.name}
                                                    </Link>
                                                </TableCell>
                                                <TableCell>
                                                    {department.faculty?.name}
                                                </TableCell>
                                                <TableCell>
                                                    {department.students_count}
                                                </TableCell>
                                                <TableCell>
                                                    {department.courses_count}
                                                </TableCell>
                                                <TableCell>
                                                    {
                                                        department.instructors_count
                                                    }
                                                </TableCell>
                                                <TableCell>
                                                    <div className="flex justify-end gap-2">
                                                        <Button
                                                            variant="outline"
                                                            size="sm"
                                                            asChild
                                                        >
                                                            <Link
                                                                href={departmentRoutes.edit(
                                                                    department.id,
                                                                )}
                                                            >
                                                                Edit
                                                            </Link>
                                                        </Button>

                                                        {isEmpty && (
                                                            <DeleteResourceDialog
                                                                action={departmentRoutes.destroy(
                                                                    department.id,
                                                                )}
                                                                title={`Delete ${department.name}?`}
                                                                description="Nothing depends on this department, so it can be removed. This cannot be undone."
                                                                iconOnly
                                                            />
                                                        )}
                                                    </div>
                                                </TableCell>
                                            </TableRow>
                                        );
                                    })}
                                </TableBody>
                            </Table>
                        )}

                        <Pagination
                            links={departments.links}
                            from={departments.from}
                            to={departments.to}
                            total={departments.total}
                        />
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

DepartmentsIndex.layout = {
    breadcrumbs: [
        { title: 'Dashboard', href: adminRoutes.dashboard() },
        { title: 'Departments', href: departmentRoutes.index() },
    ],
};
