import { Head, Link } from '@inertiajs/react';
import { DeleteResourceDialog } from '@/components/delete-resource-dialog';
import { EmptyState } from '@/components/empty-state';
import { PageHeader } from '@/components/page-header';
import { Badge } from '@/components/ui/badge';
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
import type { Course, Faculty, Instructor, Student } from '@/types';

type DepartmentShowProps = {
    department: {
        id: number;
        faculty_id: number;
        name: string;
        description: string | null;
        faculty: Faculty;
    };
    students: Student[];
    courses: Course[];
    instructors: Instructor[];
};

export default function DepartmentShow({
    department,
    students,
    courses,
    instructors,
}: DepartmentShowProps) {
    const isEmpty =
        students.length === 0 &&
        courses.length === 0 &&
        instructors.length === 0;

    return (
        <>
            <Head title={department.name} />

            <div className="flex flex-1 flex-col gap-6 p-4">
                <PageHeader
                    title={department.name}
                    description={department.description ?? undefined}
                    action={
                        <div className="flex gap-2">
                            <Button variant="outline" asChild>
                                <Link
                                    href={departmentRoutes.edit(department.id)}
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
                                />
                            )}
                        </div>
                    }
                />

                <Card>
                    <CardHeader>
                        <CardTitle>Faculty</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <Link
                            href={facultyRoutes.show(department.faculty.id)}
                            className="underline-offset-4 hover:underline"
                        >
                            {department.faculty.name}
                        </Link>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Courses</CardTitle>
                        <CardDescription>
                            Courses offered by this department.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        {courses.length === 0 ? (
                            <EmptyState title="No courses yet" />
                        ) : (
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Code</TableHead>
                                        <TableHead>Name</TableHead>
                                        <TableHead>Credit hours</TableHead>
                                        <TableHead>Instructor</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {courses.map((course) => (
                                        <TableRow key={course.id}>
                                            <TableCell className="font-medium">
                                                {course.code}
                                            </TableCell>
                                            <TableCell>{course.name}</TableCell>
                                            <TableCell>
                                                {course.credit_hours}
                                            </TableCell>
                                            <TableCell>
                                                {course.instructor?.user
                                                    ?.name ?? 'Unassigned'}
                                            </TableCell>
                                        </TableRow>
                                    ))}
                                </TableBody>
                            </Table>
                        )}
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Instructors</CardTitle>
                        <CardDescription>
                            Instructors attached to this department.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        {instructors.length === 0 ? (
                            <EmptyState title="No instructors yet" />
                        ) : (
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Employee number</TableHead>
                                        <TableHead>Name</TableHead>
                                        <TableHead>Specialization</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {instructors.map((instructor) => (
                                        <TableRow key={instructor.id}>
                                            <TableCell className="font-medium">
                                                {instructor.employee_number}
                                            </TableCell>
                                            <TableCell>
                                                {instructor.user?.name}
                                            </TableCell>
                                            <TableCell>
                                                {instructor.specialization ??
                                                    '—'}
                                            </TableCell>
                                        </TableRow>
                                    ))}
                                </TableBody>
                            </Table>
                        )}
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Students</CardTitle>
                        <CardDescription>
                            Students registered in this department.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        {students.length === 0 ? (
                            <EmptyState title="No students yet" />
                        ) : (
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Student number</TableHead>
                                        <TableHead>Name</TableHead>
                                        <TableHead>Status</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {students.map((student) => (
                                        <TableRow key={student.id}>
                                            <TableCell className="font-medium">
                                                {student.student_number}
                                            </TableCell>
                                            <TableCell>
                                                {student.first_name}{' '}
                                                {student.last_name}
                                            </TableCell>
                                            <TableCell>
                                                <Badge variant="secondary">
                                                    {student.status}
                                                </Badge>
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

DepartmentShow.layout = {
    breadcrumbs: [
        { title: 'Dashboard', href: adminRoutes.dashboard() },
        { title: 'Departments', href: departmentRoutes.index() },
    ],
};
