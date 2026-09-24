import type { LucideIcon } from 'lucide-react';
import { Card, CardContent } from '@/components/ui/card';

type StatCardProps = {
    label: string;
    value: string | number;
    icon?: LucideIcon;
    hint?: string;
};

export function StatCard({ label, value, icon: Icon, hint }: StatCardProps) {
    return (
        <Card>
            <CardContent className="flex items-start justify-between gap-4">
                <div className="space-y-1">
                    <p className="text-sm text-muted-foreground">{label}</p>
                    <p className="text-2xl font-semibold tracking-tight">
                        {value}
                    </p>
                    {hint && (
                        <p className="text-xs text-muted-foreground">{hint}</p>
                    )}
                </div>
                {Icon && (
                    <span className="rounded-lg bg-muted p-2 text-muted-foreground">
                        <Icon className="size-5" aria-hidden />
                    </span>
                )}
            </CardContent>
        </Card>
    );
}
