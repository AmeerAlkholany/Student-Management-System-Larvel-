import { Link } from '@inertiajs/react';
import { cn } from '@/lib/utils';
import type { PaginationLink } from '@/types';

type PaginationProps = {
    links: PaginationLink[];
    from: number | null;
    to: number | null;
    total: number;
};

const labels: Record<string, string> = {
    '&laquo; Previous': 'Previous',
    'Next &raquo;': 'Next',
};

export function Pagination({ links, from, to, total }: PaginationProps) {
    return (
        <div className="flex flex-wrap items-center justify-between gap-3">
            <p className="text-sm text-muted-foreground">
                {total === 0
                    ? 'No results'
                    : `Showing ${from}-${to} of ${total}`}
            </p>

            {links.length > 3 && (
                <nav aria-label="Pagination" className="flex flex-wrap gap-1">
                    {links.map((link) => {
                        const label = labels[link.label] ?? link.label;

                        return link.url === null ? (
                            <span
                                key={link.label}
                                aria-disabled="true"
                                className="rounded-md border border-transparent px-3 py-1.5 text-sm text-muted-foreground"
                            >
                                {label}
                            </span>
                        ) : (
                            <Link
                                key={link.label}
                                href={link.url}
                                aria-current={link.active ? 'page' : undefined}
                                preserveScroll
                                className={cn(
                                    'rounded-md border px-3 py-1.5 text-sm transition-colors hover:bg-accent',
                                    link.active &&
                                        'border-transparent bg-primary text-primary-foreground hover:bg-primary',
                                )}
                            >
                                {label}
                            </Link>
                        );
                    })}
                </nav>
            )}
        </div>
    );
}
