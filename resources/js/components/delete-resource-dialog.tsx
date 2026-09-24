import { Form } from '@inertiajs/react';
import { Trash2 } from 'lucide-react';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import type { RouteDefinition } from '@/wayfinder';

type DeleteResourceDialogProps = {
    action: RouteDefinition<'delete'>;
    title: string;
    description: string;
    triggerLabel?: string;
    confirmLabel?: string;
    iconOnly?: boolean;
};

/**
 * Confirmation dialog for a destroy action. The server still checks the
 * policy, so this only guards against an accidental click.
 */
export function DeleteResourceDialog({
    action,
    title,
    description,
    triggerLabel = 'Delete',
    confirmLabel = 'Delete',
    iconOnly = false,
}: DeleteResourceDialogProps) {
    return (
        <Dialog>
            <DialogTrigger asChild>
                <Button
                    variant={iconOnly ? 'ghost' : 'destructive'}
                    size={iconOnly ? 'icon' : 'default'}
                    aria-label={iconOnly ? triggerLabel : undefined}
                >
                    {iconOnly ? <Trash2 className="size-4" /> : triggerLabel}
                </Button>
            </DialogTrigger>

            <DialogContent>
                <DialogTitle>{title}</DialogTitle>
                <DialogDescription>{description}</DialogDescription>

                <Form action={action} className="mt-2">
                    {({ processing }) => (
                        <DialogFooter className="gap-2">
                            <DialogClose asChild>
                                <Button variant="secondary" type="button">
                                    Cancel
                                </Button>
                            </DialogClose>

                            <Button
                                variant="destructive"
                                type="submit"
                                disabled={processing}
                            >
                                {confirmLabel}
                            </Button>
                        </DialogFooter>
                    )}
                </Form>
            </DialogContent>
        </Dialog>
    );
}
