import { Head, Link } from '@inertiajs/react';
import { Plus } from 'lucide-react';
import { DeleteResourceDialog } from '@/components/delete-resource-dialog';
import { EmptyState } from '@/components/empty-state';
import { PageHeader } from '@/components/page-header';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
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
import facultyRoutes from '@/routes/admin/faculties';
import type { Department, Faculty } from '@/types';

type DepartmentRow = Department & {
    students_count: number;
    courses_count: number;
    instructors_count: number;
};

type FacultyShowProps = {
    faculty: Faculty;
    departments: DepartmentRow[];
};

export default function FacultyShow({
    faculty,
    departments,
}: FacultyShowProps) {
    return (
        <>
            <Head title={faculty.name} />

            <div className="flex flex-1 flex-col gap-6 p-4">
                <PageHeader
                    title={faculty.name}
                    description={faculty.description ?? undefined}
                    action={
                        <div className="flex gap-2">
                            <Button variant="outline" asChild>
                                <Link href={facultyRoutes.edit(faculty.id)}>
                                    Edit
                                </Link>
                            </Button>

                            {departments.length === 0 && (
                                <DeleteResourceDialog
                                    action={facultyRoutes.destroy(faculty.id)}
                                    title={`Delete ${faculty.name}?`}
                                    description="This faculty has no departments, so it can be removed. This cannot be undone."
                                />
                            )}
                        </div>
                    }
                />

                <Card>
                    <CardHeader>
                        <CardTitle>Departments</CardTitle>
                        <CardDescription>
                            Departments that belong to this faculty.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        {departments.length === 0 ? (
                            <EmptyState
                                title="No departments yet"
                                description="Add a department to this faculty to host courses and students."
                                action={
                                    <Button asChild className="mt-2">
                                        <Link
                                            href={departmentRoutes.create()}
                                        >
                                            <Plus className="size-4" />
                                            New department
                                        </Link>
                                    </Button>
                                }
                            />
                        ) : (
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Name</TableHead>
                                        <TableHead>Students</TableHead>
                                        <TableHead>Courses</TableHead>
                                        <TableHead>Instructors</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {departments.map((department) => (
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
                                                {department.students_count}
                                            </TableCell>
                                            <TableCell>
                                                {department.courses_count}
                                            </TableCell>
                                            <TableCell>
                                                {department.instructors_count}
                                            </TableCell>
                                        </TableRow>
                                    ))}
                                </TableBody>
                            </Table>
                        )}
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

FacultyShow.layout = {
    breadcrumbs: [
        { title: 'Dashboard', href: adminRoutes.dashboard() },
        { title: 'Faculties', href: facultyRoutes.index() },
    ],
};
