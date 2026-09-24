import { FormSelect, type SelectOption } from '@/components/form-select';
import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';

type FilterSelectProps = {
    name: string;
    label: string;
    value: string | number | null;
    options: SelectOption[];
    anyLabel?: string;
    className?: string;
};

/**
 * A labelled select for the filter bar, with an option that clears the filter.
 */
export function FilterSelect({
    name,
    label,
    value,
    options,
    anyLabel = 'All',
    className,
}: FilterSelectProps) {
    return (
        <div className={cn('grid min-w-44 gap-2', className)}>
            <Label htmlFor={name}>{label}</Label>
            <FormSelect
                id={name}
                name={name}
                defaultValue={value === null ? '' : String(value)}
                placeholder={anyLabel}
                options={options}
            />
        </div>
    );
}
