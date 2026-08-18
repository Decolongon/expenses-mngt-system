<script setup lang="ts">
import { Head, Form } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import {
    create,
    store,
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
                title: 'Create Expense',
                href: create(),
            },
        ],
    },
});

const props = defineProps<{
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

const today = new Date().toISOString().split('T')[0];

const selectedCategoryId = ref<string>('');
const selectedType = ref<string>('one-time');
const selectedRecurringInterval = ref<string>('monthly');
const autoGenerate = ref<boolean>(false);

const showRecurringFields = computed(() => selectedType.value === 'recurring');
</script>

<template>
    <Head title="Create Expense" />

    <Form :action="store()" #default="{ errors, processing }" resetOnSuccess>
        <div class="grid gap-6">
            <Card>
                <CardHeader>
                    <CardTitle>Expense Details</CardTitle>
                    <CardDescription>
                        Enter the details of the expense and the category it
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
                                :max="today"
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
                    variant="outline"
                    type="submit"
                    class="w-full sm:w-auto"
                    :disabled="processing"
                >
                    Create Expense
                </Button>
            </div>
        </div>
    </Form>
</template>
