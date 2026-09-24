import { Form, Head, Link } from '@inertiajs/react';
import FacultyController from '@/actions/App/Http/Controllers/Admin/FacultyController';
import InputError from '@/components/input-error';
import { PageHeader } from '@/components/page-header';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import adminRoutes from '@/routes/admin';
import facultyRoutes from '@/routes/admin/faculties';
import type { Faculty } from '@/types';

type FacultyEditProps = {
    faculty: Faculty;
};

export default function FacultyEdit({ faculty }: FacultyEditProps) {
    return (
        <>
            <Head title={`Edit ${faculty.name}`} />

            <div className="flex flex-1 flex-col gap-6 p-4">
                <PageHeader
                    title={`Edit ${faculty.name}`}
                    description="Rename the faculty or update its description."
                />

                <Card className="max-w-2xl">
                    <CardContent>
                        <Form
                            {...FacultyController.update.form(faculty.id)}
                            className="space-y-6"
                        >
                            {({ processing, errors }) => (
                                <>
                                    <div className="grid gap-2">
                                        <Label htmlFor="name">Name</Label>
                                        <Input
                                            id="name"
                                            name="name"
                                            required
                                            autoFocus
                                            defaultValue={faculty.name}
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
                                                faculty.description ?? ''
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
                                                href={facultyRoutes.show(
                                                    faculty.id,
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

FacultyEdit.layout = {
    breadcrumbs: [
        { title: 'Dashboard', href: adminRoutes.dashboard() },
        { title: 'Faculties', href: facultyRoutes.index() },
    ],
};
