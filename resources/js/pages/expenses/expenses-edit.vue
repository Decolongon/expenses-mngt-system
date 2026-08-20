<script setup lang="ts">
import { Head, Form } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import {
    index,
    update,
} from '@/actions/App/Http/Controllers/ExpensesController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

defineOptions({
    layout: {
        breadcrumbs: [
             {
                title: 'Expenses',
                href: index(),
            },
            {
                title: 'Edit Expense',
                href: index(),
            },
        ],
    },
});

const props = defineProps<{
    expense: {
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
        category_id: string | null;
        category: {
            id: string;
            name: string;
            color: string | null;
            icon: string | null;
        } | null;
    };
    categories: Array<{
        id: string;
        name: string;
        color: string | null;
        icon: string | null;
    }>;
}>();

const expenseTypes = [
    { value: 'one-time', label: 'One-time' },
    { value: 'recurring', label: 'Recurring' },
];

const recurringIntervals = [
    { value: 'daily', label: 'Daily' },
    { value: 'weekly', label: 'Weekly' },
    { value: 'monthly', label: 'Monthly' },
    { value: 'yearly', label: 'Yearly' },
];

const title = ref<string>(props.expense.title ?? '');
const amount = ref<string>(String(props.expense.amount ?? ''));
const date = ref<string>(props.expense.date ?? '');
const description = ref<string>(props.expense.description ?? '');
const selectedCategoryId = ref<string>(props.expense.category_id ?? '');
const selectedType = ref<string>(props.expense.type || 'one-time');
const selectedRecurringInterval = ref<string>(
    props.expense.recurring_interval || 'monthly',
);
const recurringStartDate = ref<string>(
    props.expense.recurring_start_date ?? '',
);
const recurringEndDate = ref<string>(props.expense.recurring_end_date ?? '');
const autoGenerate = ref<boolean>(Boolean(props.expense.is_auto_generated));

const showRecurringFields = computed(() => selectedType.value === 'recurring');
</script>

<template>
    <Head title="Edit Expense" />

    <Form :action="update(expense)" #default="{ errors, processing }">
        <div class="grid gap-6">
            <Card>
                <CardHeader>
                    <CardTitle>Expense Details</CardTitle>
                    <CardDescription>
                        Update the details of the expense and the category it
                        belongs to.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="grid gap-2 sm:col-span-2">
                            <Label for="title">Title</Label>
                            <Input
                                id="title"
                                name="title"
                                type="text"
                                v-model="title"
                                placeholder="e.g. Monthly rent"
                                :aria-invalid="errors.title ? 'true' : 'false'"
                            />
                            <InputError :message="errors.title" />
                        </div>

                        <div class="grid gap-2">
                            <Label>Category</Label>
                            <Select v-model="selectedCategoryId">
                                <SelectTrigger class="w-full">
                                    <SelectValue
                                        placeholder="Select a category"
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem
                                            v-for="category in props.categories"
                                            :key="category.id"
                                            :value="category.id"
                                        >
                                            {{ category.name }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <input
                                type="hidden"
                                name="category_id"
                                :value="selectedCategoryId"
                            />
                            <InputError :message="errors.category_id" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="amount">Amount</Label>
                            <div class="relative">
                                <span
                                    class="pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-sm text-muted-foreground"
                                >
                                    $
                                </span>
                                <Input
                                    id="amount"
                                    name="amount"
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    placeholder="0.00"
                                    class="pl-7"
                                    v-model="amount"
                                    :aria-invalid="
                                        errors.amount ? 'true' : 'false'
                                    "
                                />
                            </div>
                            <InputError :message="errors.amount" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="date">Date</Label>
                            <Input
                                id="date"
                                name="date"
                                type="date"
                                v-model="date"
                                :aria-invalid="errors.date ? 'true' : 'false'"
                            />
                            <InputError :message="errors.date" />
                        </div>

                        <div class="grid gap-2">
                            <Label>Type</Label>
                            <Select v-model="selectedType">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select a type" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem
                                            v-for="type in expenseTypes"
                                            :key="type.value"
                                            :value="type.value"
                                        >
                                            {{ type.label }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <input
                                type="hidden"
                                name="type"
                                :value="selectedType"
                            />
                            <InputError :message="errors.type" />
                        </div>

                        <div class="grid gap-2 sm:col-span-2">
                            <Label for="description">Description</Label>
                            <Input
                                id="description"
                                name="description"
                                type="text"
                                v-model="description"
                                placeholder="What was this expense for?"
                                :aria-invalid="
                                    errors.description ? 'true' : 'false'
                                "
                            />
                            <InputError :message="errors.description" />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card v-if="showRecurringFields">
                <CardHeader>
                    <CardTitle>Recurring Settings</CardTitle>
                    <CardDescription>
                        Configure how often this expense repeats.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="grid gap-2">
                            <Label>Recurring Interval</Label>
                            <Select v-model="selectedRecurringInterval">
                                <SelectTrigger class="w-full">
                                    <SelectValue
                                        placeholder="Select an interval"
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem
                                            v-for="interval in recurringIntervals"
                                            :key="interval.value"
                                            :value="interval.value"
                                        >
                                            {{ interval.label }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <input
                                type="hidden"
                                name="recurring_interval"
                                :value="selectedRecurringInterval"
                            />
                            <InputError :message="errors.recurring_interval" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="recurring_start_date">
                                Recurring Start Date
                            </Label>
                            <Input
                                id="recurring_start_date"
                                name="recurring_start_date"
                                type="date"
                                v-model="recurringStartDate"
                                :aria-invalid="
                                    errors.recurring_start_date
                                        ? 'true'
                                        : 'false'
                                "
                            />
                            <InputError
                                :message="errors.recurring_start_date"
                            />
                        </div>

                        <div class="grid gap-2">
                            <Label for="recurring_end_date">
                                Recurring End Date
                            </Label>
                            <Input
                                id="recurring_end_date"
                                name="recurring_end_date"
                                type="date"
                                v-model="recurringEndDate"
                                :aria-invalid="
                                    errors.recurring_end_date ? 'true' : 'false'
                                "
                            />
                            <InputError :message="errors.recurring_end_date" />
                        </div>

                        <div class="grid gap-2">
                            <Label class="mb-2">Auto-generate</Label>
                            <div class="flex items-center gap-2 pt-2">
                                <Checkbox
                                    id="is_auto_generated"
                                    :checked="autoGenerate"
                                    @update:checked="
                                        (value: boolean | 'indeterminate') =>
                                            (autoGenerate = value === true)
                                    "
                                />
                                <label
                                    for="is_auto_generated"
                                    class="text-sm text-muted-foreground"
                                >
                                    Automatically generate future expenses
                                </label>
                            </div>
                            <input
                                type="hidden"
                                name="is_auto_generated"
                                :value="autoGenerate ? '1' : '0'"
                            />
                            <InputError :message="errors.is_auto_generated" />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
                <Button
                    type="submit"
                    variant="outline"
                    class="w-full sm:w-auto"
                    :disabled="processing"
                >
                    Update Expense
                </Button>
            </div>
        </div>
    </Form>
</template>
