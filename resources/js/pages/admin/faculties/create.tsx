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

export default function FacultyCreate() {
    return (
        <>
            <Head title="New faculty" />

            <div className="flex flex-1 flex-col gap-6 p-4">
                <PageHeader
                    title="New faculty"
                    description="A faculty groups the departments of the institution."
                />

                <Card className="max-w-2xl">
                    <CardContent>
                        <Form
                            {...FacultyController.store.form()}
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
                                            placeholder="Faculty of Engineering"
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
                                            placeholder="Optional summary of the faculty."
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
                                            Create faculty
                                        </Button>
                                        <Button variant="ghost" asChild>
                                            <Link href={facultyRoutes.index()}>
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

FacultyCreate.layout = {
    breadcrumbs: [
        { title: 'Dashboard', href: adminRoutes.dashboard() },
        { title: 'Faculties', href: facultyRoutes.index() },
        { title: 'New faculty', href: facultyRoutes.create() },
    ],
};
