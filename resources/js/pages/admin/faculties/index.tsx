import { Head, Link } from '@inertiajs/react';
import { Plus } from 'lucide-react';
import { DeleteResourceDialog } from '@/components/delete-resource-dialog';
import { EmptyState } from '@/components/empty-state';
import { FilterBar } from '@/components/filter-bar';
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
import facultyRoutes from '@/routes/admin/faculties';
import type { Faculty, Paginated } from '@/types';

type FacultyRow = Faculty & {
    departments_count: number;
    students_count: number;
    courses_count: number;
};

type FacultiesIndexProps = {
    faculties: Paginated<FacultyRow>;
    filters: { search: string | null };
};

export default function FacultiesIndex({
    faculties,
    filters,
}: FacultiesIndexProps) {
    return (
        <>
            <Head title="Faculties" />

            <div className="flex flex-1 flex-col gap-6 p-4">
                <PageHeader
                    title="Faculties"
                    description="Every faculty and the departments it owns."
                    action={
                        <Button asChild>
                            <Link href={facultyRoutes.create()}>
                                <Plus className="size-4" />
                                New faculty
                            </Link>
                        </Button>
                    }
                />

                <Card>
                    <CardContent className="space-y-4">
                        <FilterBar
                            action={facultyRoutes.index()}
                            search={filters.search}
                            searchPlaceholder="Faculty name"
                            hasFilters={Boolean(filters.search)}
                        />

                        {faculties.data.length === 0 ? (
                            <EmptyState
                                title="No faculties found"
                                description="Create a faculty to start building the academic structure."
                            />
                        ) : (
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Name</TableHead>
                                        <TableHead>Departments</TableHead>
                                        <TableHead>Courses</TableHead>
                                        <TableHead>Students</TableHead>
                                        <TableHead className="text-right">
                                            Actions
                                        </TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {faculties.data.map((faculty) => (
                                        <TableRow key={faculty.id}>
                                            <TableCell className="font-medium">
                                                <Link
                                                    href={facultyRoutes.show(
                                                        faculty.id,
                                                    )}
                                                    className="underline-offset-4 hover:underline"
                                                >
                                                    {faculty.name}
                                                </Link>
                                            </TableCell>
                                            <TableCell>
                                                {faculty.departments_count}
                                            </TableCell>
                                            <TableCell>
                                                {faculty.courses_count}
                                            </TableCell>
                                            <TableCell>
                                                {faculty.students_count}
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex justify-end gap-2">
                                                    <Button
                                                        variant="outline"
                                                        size="sm"
                                                        asChild
                                                    >
                                                        <Link
                                                            href={facultyRoutes.edit(
                                                                faculty.id,
                                                            )}
                                                        >
                                                            Edit
                                                        </Link>
                                                    </Button>

                                                    {faculty.departments_count ===
                                                        0 && (
                                                        <DeleteResourceDialog
                                                            action={facultyRoutes.destroy(
                                                                faculty.id,
                                                            )}
                                                            title={`Delete ${faculty.name}?`}
                                                            description="This faculty has no departments, so it can be removed. This cannot be undone."
                                                            iconOnly
                                                        />
                                                    )}
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ))}
                                </TableBody>
                            </Table>
                        )}

                        <Pagination
                            links={faculties.links}
                            from={faculties.from}
                            to={faculties.to}
                            total={faculties.total}
                        />
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

FacultiesIndex.layout = {
    breadcrumbs: [
        { title: 'Dashboard', href: adminRoutes.dashboard() },
        { title: 'Faculties', href: facultyRoutes.index() },
    ],
};
