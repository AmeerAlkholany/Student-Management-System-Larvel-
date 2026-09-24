import { Head } from '@inertiajs/react';
import { BookOpen, GraduationCap, UsersRound } from 'lucide-react';
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
import instructorRoutes from '@/routes/instructor';
import type { Course, Enrollment } from '@/types';

type InstructorDashboardProps = {
    stats: {
        courses: number;
        students: number;
        activeEnrollments: number;
    };
    courses: Course[];
    recentEnrollments: Enrollment[];
};

export default function InstructorDashboard({
    stats,
    courses,
    recentEnrollments,
}: InstructorDashboardProps) {
    return (
        <>
            <Head title="Instructor dashboard" />
            <div className="flex flex-1 flex-col gap-6 p-4">
                <Heading
                    title="Instructor dashboard"
                    description="The courses assigned to you and the students taking them."
                />

                <div className="grid gap-4 sm:grid-cols-3">
                    <StatCard
                        label="Assigned courses"
                        value={stats.courses}
                        icon={BookOpen}
                    />
                    <StatCard
                        label="Students"
                        value={stats.students}
                        icon={GraduationCap}
                    />
                    <StatCard
                        label="Active enrollments"
                        value={stats.activeEnrollments}
                        icon={UsersRound}
                    />
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>Your courses</CardTitle>
                        <CardDescription>
                            Record attendance and enter grades from a course.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        {courses.length === 0 ? (
                            <EmptyState
                                title="No courses assigned"
                                description="An administrator has not assigned you to a course yet."
                            />
                        ) : (
                            <ul className="divide-y">
                                {courses.map((course) => (
                                    <li
                                        key={course.id}
                                        className="flex flex-wrap items-center justify-between gap-2 py-3"
                                    >
                                        <div>
                                            <p className="font-medium">
                                                {course.code} {course.name}
                                            </p>
                                            <p className="text-sm text-muted-foreground">
                                                {course.department?.name}{' '}
                                                &middot; {course.credit_hours}{' '}
                                                credit hours
                                            </p>
                                        </div>
                                        <Badge variant="secondary">
                                            {course.enrollments_count ?? 0}{' '}
                                            enrolled
                                        </Badge>
                                    </li>
                                ))}
                            </ul>
                        )}
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Recent enrollments</CardTitle>
                        <CardDescription>
                            The latest students to join one of your courses.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        {recentEnrollments.length === 0 ? (
                            <EmptyState title="No enrollments yet" />
                        ) : (
                            <ul className="divide-y">
                                {recentEnrollments.map((enrollment) => (
                                    <li
                                        key={enrollment.id}
                                        className="flex flex-wrap items-center justify-between gap-2 py-3"
                                    >
                                        <div>
                                            <p className="font-medium">
                                                {enrollment.student
                                                    ?.first_name}{' '}
                                                {enrollment.student?.last_name}
                                            </p>
                                            <p className="text-sm text-muted-foreground">
                                                {
                                                    enrollment.student
                                                        ?.student_number
                                                }{' '}
                                                &middot;{' '}
                                                {enrollment.course?.code}
                                            </p>
                                        </div>
                                        <Badge variant="secondary">
                                            {enrollment.status}
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

InstructorDashboard.layout = {
    breadcrumbs: [
        {
            title: 'Dashboard',
            href: instructorRoutes.dashboard(),
        },
    ],
};
