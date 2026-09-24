import { Head } from '@inertiajs/react';
import { BookOpen, CalendarCheck, GraduationCap } from 'lucide-react';
import { EmptyState } from '@/components/empty-state';
import Heading from '@/components/heading';
import { StatCard } from '@/components/stat-card';
import { Badge } from '@/components/ui/badge';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import studentRoutes from '@/routes/student';
import type { Enrollment, Grade, Semester, StudentStatus } from '@/types';

type StudentDashboardProps = {
    student: {
        id: number;
        studentNumber: string;
        fullName: string;
        department: string;
        status: StudentStatus;
        attendancePercentage: number;
    };
    currentSemester: Semester | null;
    enrollments: Enrollment[];
    latestGrades: (Grade & { enrollment?: Enrollment })[];
};

export default function StudentDashboard({
    student,
    currentSemester,
    enrollments,
    latestGrades,
}: StudentDashboardProps) {
    return (
        <>
            <Head title="Student dashboard" />
            <div className="flex flex-1 flex-col gap-6 p-4">
                <Heading
                    title={student.fullName}
                    description={`${student.studentNumber} · ${student.department}`}
                />

                <div className="grid gap-4 sm:grid-cols-3">
                    <StatCard
                        label="Enrolled courses"
                        value={enrollments.length}
                        icon={BookOpen}
                        hint={currentSemester?.name ?? 'No current semester'}
                    />
                    <StatCard
                        label="Attendance"
                        value={`${student.attendancePercentage}%`}
                        icon={CalendarCheck}
                    />
                    <StatCard
                        label="Status"
                        value={student.status}
                        icon={GraduationCap}
                    />
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>Current courses</CardTitle>
                        <CardDescription>
                            {currentSemester
                                ? `Courses you are taking in the ${currentSemester.name}.`
                                : 'Courses you are taking.'}
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        {enrollments.length === 0 ? (
                            <EmptyState
                                title="No courses yet"
                                description="You are not enrolled in any course for this semester."
                            />
                        ) : (
                            <ul className="divide-y">
                                {enrollments.map((enrollment) => (
                                    <li
                                        key={enrollment.id}
                                        className="flex flex-wrap items-center justify-between gap-2 py-3"
                                    >
                                        <div>
                                            <p className="font-medium">
                                                {enrollment.course?.code}{' '}
                                                {enrollment.course?.name}
                                            </p>
                                            <p className="text-sm text-muted-foreground">
                                                {
                                                    enrollment.course
                                                        ?.credit_hours
                                                }{' '}
                                                credit hours &middot;{' '}
                                                {enrollment.status}
                                            </p>
                                        </div>
                                        <Badge variant="secondary">
                                            {enrollment.grade
                                                ? `${enrollment.grade.letter_grade ?? '—'} (${enrollment.grade.total})`
                                                : 'Not graded'}
                                        </Badge>
                                    </li>
                                ))}
                            </ul>
                        )}
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Latest grades</CardTitle>
                        <CardDescription>
                            Your most recently published results.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        {latestGrades.length === 0 ? (
                            <EmptyState title="No grades published yet" />
                        ) : (
                            <ul className="divide-y">
                                {latestGrades.map((grade) => (
                                    <li
                                        key={grade.id}
                                        className="flex flex-wrap items-center justify-between gap-2 py-3"
                                    >
                                        <div>
                                            <p className="font-medium">
                                                {
                                                    grade.enrollment?.course
                                                        ?.code
                                                }{' '}
                                                {
                                                    grade.enrollment?.course
                                                        ?.name
                                                }
                                            </p>
                                            <p className="text-sm text-muted-foreground">
                                                Total {grade.total} / 100
                                            </p>
                                        </div>
                                        <Badge>
                                            {grade.letter_grade ?? '—'}
                                        </Badge>
                                    </li>
                                ))}
                            </ul>
                        )}
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

StudentDashboard.layout = {
    breadcrumbs: [
        {
            title: 'Dashboard',
            href: studentRoutes.dashboard(),
        },
    ],
};
