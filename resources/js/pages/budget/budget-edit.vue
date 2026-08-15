<script setup lang="ts">
import { Head, Form } from '@inertiajs/vue3';
import { ref } from 'vue';
import { index, update } from '@/actions/App/Http/Controllers/BudgetController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
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
                title: 'Edit Budget',
                href: index(),
            },
        ],
    },
});

const props = defineProps<{
    budget: {
        id: string;
        amount: string | number;
        month: number;
        year: number;
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

const months = [
    { value: '1', label: 'January' },
    { value: '2', label: 'February' },
    { value: '3', label: 'March' },
    { value: '4', label: 'April' },
    { value: '5', label: 'May' },
    { value: '6', label: 'June' },
    { value: '7', label: 'July' },
    { value: '8', label: 'August' },
    { value: '9', label: 'September' },
    { value: '10', label: 'October' },
    { value: '11', label: 'November' },
    { value: '12', label: 'December' },
];

const today = new Date();
const currentYear = today.getFullYear();
const currentMonth = (today.getMonth() + 1).toString();
const years = Array.from({ length: 5 }, (_, index) => currentYear + index);

const amount = ref<string>(String(props.budget.amount ?? ''));
const selectedCategoryId = ref<string>(props.budget.category_id ?? '');
const selectedMonth = ref<string>(String(props.budget.month) || currentMonth);
const selectedYear = ref<string>(
    String(props.budget.year) || currentYear.toString(),
);
</script>

<template>
    <Head title="Edit Budget" />

    <Form :action="update(budget)" #default="{ errors, processing }">
        <div class="grid gap-6">
            <Card>
                <CardHeader>
                    <CardTitle>Budget Details</CardTitle>
                    <CardDescription>
                        Set the amount to allocate and the category it applies
                        to.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid gap-4 sm:grid-cols-2">
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
                                    v-model="amount"
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
                                            v-for="category in categories"
                                            :key="category.id"
                                            :value="category.id"
                                        >
                                            <span
                                                v-if="category.color"
                                                class="size-2 shrink-0 rounded-full"
                                                :style="{
                                                    backgroundColor:
                                                        category.color,
                                                }"
                                            />
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
                            <p
                                v-if="!categories.length"
                                class="text-sm text-muted-foreground"
                            >
                                No categories yet. Create a category first.
                            </p>
                            <InputError :message="errors.category_id" />
                        </div>
                    </div>
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle>Budget Period</CardTitle>
                    <CardDescription>
                        Choose the month and year this budget covers.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="grid gap-2">
                            <Label>Month</Label>
                            <Select v-model="selectedMonth">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select a month" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem
                                            v-for="month in months"
                                            :key="month.value"
                                            :value="month.value"
                                        >
                                            {{ month.label }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <input
                                type="hidden"
                                name="month"
                                :value="selectedMonth"
                            />
                            <InputError :message="errors.month" />
                        </div>

                        <div class="grid gap-2">
                            <Label>Year</Label>
                            <Select v-model="selectedYear">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select a year" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem
                                            v-for="year in years"
                                            :key="year"
                                            :value="year.toString()"
                                        >
                                            {{ year }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <input
                                type="hidden"
                                name="year"
                                :value="selectedYear"
                            />
                            <InputError :message="errors.year" />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
                <Button
                    type="submit"
                    class="w-full sm:w-auto"
                    :disabled="processing"
                >
                    Update Budget
                </Button>
            </div>
        </div>
    </Form>
</template>
