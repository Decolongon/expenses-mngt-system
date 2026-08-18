<script setup lang="ts">
import { Head, Link, Form } from '@inertiajs/vue3';
import { icons as lucideIcons } from '@lucide/vue';
import {
    create,
    destroy,
    edit,
    index,
} from '@/actions/App/Http/Controllers/ExpensesController';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Expenses',
                href: index(),
            },
        ],
    },
});

const props = defineProps<{
    expenses: Array<{
        id: string;
        amount: string | number;
        title: string;
        description: string | null;
        date: string;
        type: string;
        recurring_interval: string | null;
        recurring_start_date: string | null;
        recurring_end_date: string | null;
        is_auto_generated: boolean;
        category: {
            id: string;
            name: string;
            color: string | null;
            icon: string | null;
        } | null;
    }>;
}>();

const DEFAULT_COLOR = '#e2e8f0';
const DEFAULT_ICON = 'ReceiptText';

const currencyFormatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
});

function formattedAmount(amount: string | number): string {
    return currencyFormatter.format(Number(amount) || 0);
}

function formattedDate(date: string): string {
    if (!date) {
        return '';
    }

    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
}

function expenseType(type: string): string {
    return type.charAt(0).toUpperCase() + type.slice(1);
}

function iconComponent(name: string | null): any {
    const icons = lucideIcons as Record<string, any>;

    return icons[name ?? ''] ?? icons[DEFAULT_ICON];
}

function backgroundColor(color: string | null): string {
    return color || DEFAULT_COLOR;
}

function textColor(color: string | null): string {
    const hex = (color || DEFAULT_COLOR).replace('#', '');

    const r = parseInt(hex.substring(0, 2), 16);
    const g = parseInt(hex.substring(2, 4), 16);
    const b = parseInt(hex.substring(4, 6), 16);

    const luminance = 0.299 * r + 0.587 * g + 0.114 * b;

    return luminance > 150 ? '#111827' : '#ffffff';
}
</script>

<template>
    <Head title="Expenses" />

    <div class="flex h-full flex-1 flex-col gap-4 p-4">
        <Card>
            <CardHeader>
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <CardTitle>Expenses</CardTitle>
                        <CardDescription>
                            Track how much you spend in each category.
                        </CardDescription>
                    </div>
                    <Button variant="outline" as-child>
                        <Link :href="create.url()">Create Expense</Link>
                    </Button>
                </div>
            </CardHeader>
            <CardContent>
                <div v-if="props.expenses.length" class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr
                                class="border-b text-left text-muted-foreground"
                            >
                                <th class="px-4 py-3 font-medium">Category</th>
                                <th class="px-4 py-3 font-medium">Title</th>
                                <th class="px-4 py-3 font-medium">Amount</th>
                                <th class="px-4 py-3 font-medium">Date</th>
                                <th class="px-4 py-3 font-medium">Type</th>
                                <th class="px-4 py-3 text-right font-medium">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="expense in props.expenses"
                                :key="expense.id"
                                class="border-b last:border-0"
                            >
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="inline-flex size-10 shrink-0 items-center justify-center rounded-xl"
                                            :style="{
                                                backgroundColor:
                                                    backgroundColor(
                                                        expense.category
                                                            ?.color ?? null,
                                                    ),
                                                color: textColor(
                                                    expense.category?.color ??
                                                        null,
                                                ),
                                            }"
                                        >
                                            <component
                                                :is="
                                                    iconComponent(
                                                        expense.category
                                                            ?.icon ?? null,
                                                    )
                                                "
                                                :size="20"
                                            />
                                        </span>
                                        <span class="font-medium">
                                            {{
                                                expense.category?.name ??
                                                'Uncategorized'
                                            }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col">
                                        <span class="font-medium">
                                            {{ expense.title }}
                                        </span>
                                        <span
                                            v-if="expense.description"
                                            class="max-w-64 truncate text-xs text-muted-foreground"
                                        >
                                            {{ expense.description }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 font-medium tabular-nums">
                                    {{ formattedAmount(expense.amount) }}
                                </td>
                                <td class="px-4 py-3 text-muted-foreground">
                                    {{ formattedDate(expense.date) }}
                                </td>
                                <td class="px-4 py-3 text-muted-foreground">
                                    {{ expenseType(expense.type) }}
                                    <span
                                        v-if="
                                            expense.type === 'recurring' &&
                                            expense.recurring_interval
                                        "
                                        class="text-xs"
                                    >
                                        · {{ expense.recurring_interval }}
                                    </span>
                                </td>

                                <td class="px-4 py-3">
                                    <div
                                        class="flex items-center justify-end gap-2"
                                    >
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            as-child
                                        >
                                            <Link :href="edit.url(expense)"
                                                >Edit</Link
                                            >
                                        </Button>

                                        <Dialog>
                                            <DialogTrigger as-child>
                                                <Button
                                                    variant="destructive"
                                                    size="sm"
                                                >
                                                    Delete
                                                </Button>
                                            </DialogTrigger>
                                            <DialogContent>
                                                <Form
                                                    :action="destroy(expense)"
                                                    method="delete"
                                                    :options="{
                                                        preserveScroll: true,
                                                    }"
                                                    class="space-y-6"
                                                    v-slot="{ processing }"
                                                >
                                                    <DialogHeader
                                                        class="space-y-3"
                                                    >
                                                        <DialogTitle>
                                                            Delete "{{
                                                                expense.title
                                                            }}" expense?
                                                        </DialogTitle>
                                                        <DialogDescription>
                                                            This will
                                                            permanently delete
                                                            the
                                                            {{
                                                                formattedDate(
                                                                    expense.date,
                                                                )
                                                            }}
                                                            expense for
                                                            {{
                                                                expense.category
                                                                    ?.name ??
                                                                'uncategorized'
                                                            }}. This action
                                                            cannot be undone.
                                                        </DialogDescription>
                                                    </DialogHeader>

                                                    <DialogFooter class="gap-2">
                                                        <DialogClose as-child>
                                                            <Button
                                                                type="button"
                                                                variant="secondary"
                                                            >
                                                                Cancel
                                                            </Button>
                                                        </DialogClose>
                                                        <Button
                                                            type="submit"
                                                            variant="destructive"
                                                            :disabled="
                                                                processing
                                                            "
                                                        >
                                                            Delete
                                                        </Button>
                                                    </DialogFooter>
                                                </Form>
                                            </DialogContent>
                                        </Dialog>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-else
                    class="py-12 text-center text-sm text-muted-foreground"
                >
                    No expenses yet.
                </div>
            </CardContent>
        </Card>
    </div>
</template>
