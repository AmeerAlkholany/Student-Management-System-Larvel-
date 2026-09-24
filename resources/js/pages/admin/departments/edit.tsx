import { Form, Head, Link } from '@inertiajs/react';
import DepartmentController from '@/actions/App/Http/Controllers/Admin/DepartmentController';
import { FormSelect } from '@/components/form-select';
import InputError from '@/components/input-error';
import { PageHeader } from '@/components/page-header';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import adminRoutes from '@/routes/admin';
import departmentRoutes from '@/routes/admin/departments';
import type { Faculty } from '@/types';

type DepartmentEditProps = {
    department: {
        id: number;
        faculty_id: number;
        name: string;
        description: string | null;
    };
    faculties: Faculty[];
};

export default function DepartmentEdit({
    department,
    faculties,
}: DepartmentEditProps) {
    return (
        <>
            <Head title={`Edit ${department.name}`} />

            <div className="flex flex-1 flex-col gap-6 p-4">
                <PageHeader
                    title={`Edit ${department.name}`}
                    description="Move the department to another faculty or update its details."
                />

                <Card className="max-w-2xl">
                    <CardContent>
                        <Form
                            {...DepartmentController.update.form(department.id)}
                            className="space-y-6"
                        >
                            {({ processing, errors }) => (
                                <>
                                    <div className="grid gap-2">
                                        <Label htmlFor="faculty_id">
                                            Faculty
                                        </Label>
                                        <FormSelect
                                            id="faculty_id"
                                            name="faculty_id"
                                            required
                                            defaultValue={
                                                department.faculty_id
                                            }
                                            options={faculties.map(
                                                (faculty) => ({
                                                    value: faculty.id,
                                                    label: faculty.name,
                                                }),
                                            )}
                                        />
                                        <InputError
                                            message={errors.faculty_id}
                                        />
                                    </div>

                                    <div className="grid gap-2">
                                        <Label htmlFor="name">Name</Label>
                                        <Input
                                            id="name"
                                            name="name"
                                            required
                                            defaultValue={department.name}
                                        />
                                        <InputError message={errors.name} />
                                    </div>

                                    <div className="grid gap-2">
                                        <Label htmlFor="description">
                                            Description
                                        </Label>
                                        <Textarea
                                            id="description"
                                            name="description"
                                            rows={4}
                                            defaultValue={
                                                department.description ?? ''
                                            }
                                        />
                                        <InputError
                                            message={errors.description}
                                        />
                                    </div>

                                    <div className="flex gap-2">
                                        <Button
                                            type="submit"
                                            disabled={processing}
                                        >
                                            Save changes
                                        </Button>
                                        <Button variant="ghost" asChild>
                                            <Link
                                                href={departmentRoutes.show(
                                                    department.id,
                                                )}
                                            >
                                                Cancel
                                            </Link>
                                        </Button>
                                    </div>
                                </>
                            )}
                        </Form>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

DepartmentEdit.layout = {
    breadcrumbs: [
        { title: 'Dashboard', href: adminRoutes.dashboard() },
        { title: 'Departments', href: departmentRoutes.index() },
    ],
};
