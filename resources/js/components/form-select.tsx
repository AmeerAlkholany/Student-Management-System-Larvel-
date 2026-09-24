import type { ComponentProps } from 'react';
import { cn } from '@/lib/utils';

export type SelectOption = {
    value: string | number;
    label: string;
};

type FormSelectProps = Omit<ComponentProps<'select'>, 'children'> & {
    options: SelectOption[];
    placeholder?: string;
};

/**
 * A native select styled like the Input primitive. Native keeps the value in
 * the surrounding form's data without any extra wiring.
 */
export function FormSelect({
    options,
    placeholder,
    className,
    ...props
}: FormSelectProps) {
    return (
        <select
            data-slot="select"
            className={cn(
                'h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-xs outline-none',
                'focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50',
                'aria-invalid:border-destructive aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40',
                'disabled:cursor-not-allowed disabled:opacity-50',
                className,
            )}
            {...props}
        >
            {placeholder !== undefined && (
                <option value="">{placeholder}</option>
            )}
            {options.map((option) => (
                <option key={option.value} value={option.value}>
                    {option.label}
                </option>
            ))}
        </select>
    );
}
