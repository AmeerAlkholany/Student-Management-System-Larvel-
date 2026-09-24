import { Form, Link } from '@inertiajs/react';
import type { ReactNode } from 'react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { RouteDefinition } from '@/wayfinder';

type FilterBarProps = {
    action: RouteDefinition<'get'>;
    search: string | null;
    searchLabel?: string;
    searchPlaceholder?: string;
    hasFilters?: boolean;
    children?: ReactNode;
};

/**
 * A GET form whose values stay in the URL, so every filtered list is
 * shareable and survives a refresh.
 */
export function FilterBar({
    action,
    search,
    searchLabel = 'Search',
    searchPlaceholder = 'Search...',
    hasFilters = false,
    children,
}: FilterBarProps) {
    return (
        <Form
            action={action}
            options={{ preserveState: true, replace: true }}
            transform={(data) =>
                Object.fromEntries(
                    Object.entries(data).filter(
                        ([, value]) => value !== '' && value !== null,
                    ),
                )
            }
            className="flex flex-wrap items-end gap-3"
        >
            {({ processing }) => (
                <>
                    <div className="grid min-w-56 flex-1 gap-2">
                        <Label htmlFor="search">{searchLabel}</Label>
                        <Input
                            id="search"
                            name="search"
                            type="search"
                            defaultValue={search ?? ''}
                            placeholder={searchPlaceholder}
                        />
                    </div>

                    {children}

                    <div className="flex gap-2">
                        <Button type="submit" disabled={processing}>
                            Apply
                        </Button>

                        {hasFilters && (
                            <Button variant="ghost" asChild>
                                <Link href={action}>Reset</Link>
                            </Button>
                        )}
                    </div>
                </>
            )}
        </Form>
    );
}
