import { Head } from '@inertiajs/react';
import {
    BookOpen,
    Building2,
    GraduationCap,
    Layers,
    UserCheck,
    Users,
    UsersRound,
} from 'lucide-react';
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
import adminRoutes from '@/routes/admin';
import type { Enrollment, Semester } from '@/types';

type AdminDashboardProps = {
    stats: {
        students: number;
        activeStudents: number;
        instructors: number;
        faculties: number;
        departments: number;
        courses: number;
        enrollments: number;
    };
    currentSemester: Semester | null;
    recentEnrollments: Enrollment[];
};

export default function AdminDashboard({
    stats,
    currentSemester,
    recentEnrollments,
}: AdminDashboardProps) {
    return (
        <>
            <Head title="Admin dashboard" />
            <div className="flex flex-1 flex-col gap-6 p-4">
                <Heading
                    title="Admin dashboard"
                    description={
                        currentSemester
                            ? `${currentSemester.name}, ${currentSemester.academic_year?.name ?? ''}`.trim()
                            : 'No semester is marked as current.'
                    }
                />

                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <StatCard
                        label="Students"
                        value={stats.students}
                        icon={GraduationCap}
                        hint={`${stats.activeStudents} active`}
                    />
                    <StatCard
                        label="Instructors"
                        value={stats.instructors}
                        icon={Users}
                    />
                    <StatCard
                        label="Faculties"
                        value={stats.faculties}
                        icon={Building2}
                    />
                    <StatCard
                        label="Departments"
                        value={stats.departments}
                        icon={Layers}
                    />
                    <StatCard
                        label="Courses"
                        value={stats.courses}
                        icon={BookOpen}
                    />
                    <StatCard
                        label="Enrollments"
                        value={stats.enrollments}
                        icon={UsersRound}
                    />
                    <StatCard
                        label="Active students"
                        value={stats.activeStudents}
                        icon={UserCheck}
                    />
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>Recent enrollments</CardTitle>
                        <CardDescription>
                            The most recent students to join a course.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        {recentEnrollments.length === 0 ? (
                            <EmptyState
                                title="No enrollments yet"
                                description="Enrollments will appear here once students join courses."
                            />
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
                                                {enrollment.course?.code}{' '}
                                                {enrollment.course?.name}
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

AdminDashboard.layout = {
    breadcrumbs: [
        {
            title: 'Dashboard',
            href: adminRoutes.dashboard(),
        },
    ],
};
