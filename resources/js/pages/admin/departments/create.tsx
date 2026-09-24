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

type DepartmentCreateProps = {
    faculties: Faculty[];
};

export default function DepartmentCreate({
    faculties,
}: DepartmentCreateProps) {
    return (
        <>
            <Head title="New department" />

            <div className="flex flex-1 flex-col gap-6 p-4">
                <PageHeader
                    title="New department"
                    description="A department belongs to one faculty and owns its courses."
                />

                <Card className="max-w-2xl">
                    <CardContent>
                        <Form
                            {...DepartmentController.store.form()}
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
                                            placeholder="Select a faculty"
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
                                            placeholder="Computer Science"
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
                                            Create department
                                        </Button>
                                        <Button variant="ghost" asChild>
                                            <Link
                                                href={departmentRoutes.index()}
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

DepartmentCreate.layout = {
    breadcrumbs: [
        { title: 'Dashboard', href: adminRoutes.dashboard() },
        { title: 'Departments', href: departmentRoutes.index() },
        { title: 'New department', href: departmentRoutes.create() },
    ],
};
