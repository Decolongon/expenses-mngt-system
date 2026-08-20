<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { icons as lucideIcons } from '@lucide/vue';
import { create as createExpense } from '@/actions/App/Http/Controllers/ExpensesController';
import { create as createBudget } from '@/actions/App/Http/Controllers/BudgetController';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { dashboard } from '@/routes';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});

interface DashboardCategory {
    id: string | null;
    name: string;
    color: string | null;
    icon: string | null;
}

interface DashboardExpense {
    id: string;
    amount: string | number;
    title: string;
    description: string | null;
    date: string;
    type: string;
    recurring_interval: string | null;
    recurring_start_date: string | null;
    recurring_end_date: string | null;
    category: DashboardCategory | null;
}

const props = defineProps<{
    stats: {
        totalSpent: number;
        totalBudget: number;
        remaining: number;
        expenseCount: number;
        categoryCount: number;
    };
    monthlySpending: Array<{ label: string; total: number }>;
    spendingByCategory: Array<
        DashboardCategory & { total: number; percent: number }
    >;
    budgetProgress: Array<{
        id: string;
        amount: number;
        spent: number;
        percent: number;
        category: DashboardCategory | null;
    }>;
    recentExpenses: Array<DashboardExpense>;
    recurringExpenses: Array<DashboardExpense>;
}>();

const page = usePage();
const userName = page.props.auth?.user?.name ?? 'there';

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

function capFirst(value: string): string {
    return value.charAt(0).toUpperCase() + value.slice(1);
}

function greeting(): string {
    const hour = new Date().getHours();

    if (hour < 12) {
        return 'Good morning';
    }

    if (hour < 18) {
        return 'Good afternoon';
    }

    return 'Good evening';
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

const maxMonthlySpending = computed(() =>
    Math.max(
        1,
        ...props.monthlySpending.map((entry) => Number(entry.total) || 0),
    ),
);

const statCards = computed(() => [
    {
        label: 'Total spent',
        value: formattedAmount(props.stats.totalSpent),
        hint: 'This month',
        icon: 'Wallet',
    },
    {
        label: 'Budget',
        value: formattedAmount(props.stats.totalBudget),
        hint: 'This month',
        icon: 'PiggyBank',
    },
    {
        label: 'Remaining',
        value: formattedAmount(props.stats.remaining),
        hint: 'After spending',
        icon: 'CircleDollarSign',
    },
    {
        label: 'Expenses',
        value: String(props.stats.expenseCount),
        hint: 'This month',
        icon: 'Receipt',
    },
]);
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex h-full w-full flex-col gap-6 overflow-x-auto p-4 lg:p-6">
        <!-- ===================== Header ===================== -->
        <div
            class="flex flex-wrap items-center justify-between gap-4 rounded-xl border border-sidebar-border/70 bg-white p-5 dark:border-sidebar-border dark:bg-[hsl(223_47%_9%)]"
        >
            <div>
                <h1
                    class="text-2xl font-bold tracking-tight text-foreground sm:text-3xl"
                >
                    {{ greeting() }}, {{ userName }}
                </h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    Here's a quick look at your spending this month.
                </p>
            </div>
            <Button variant="outline" as-child>
                <Link :href="createExpense.url()" prefetch="click">Add expense</Link>
            </Button>
        </div>

        <!-- ===================== Stat cards ===================== -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <Card
                v-for="card in statCards"
                :key="card.label"
                class="rounded-xl"
            >
                <CardContent class="flex items-start justify-between p-5">
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">
                            {{ card.label }}
                        </p>
                        <p class="mt-2 text-2xl font-bold text-foreground">
                            {{ card.value }}
                        </p>
                        <p class="mt-1 text-xs text-muted-foreground">
                            {{ card.hint }}
                        </p>
                    </div>
                    <span
                        class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-secondary/15 text-secondary dark:bg-secondary/25 dark:text-[hsl(217_91%_70%)]"
                    >
                        <component
                            :is="iconComponent(card.icon)"
                            class="size-5"
                        />
                    </span>
                </CardContent>
            </Card>
        </div>

        <!-- ===================== Charts ===================== -->
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <Card class="rounded-xl">
                <CardHeader>
                    <CardTitle>Spending by category</CardTitle>
                    <CardDescription
                        >This month's top categories</CardDescription
                    >
                </CardHeader>
                <CardContent>
                    <div
                        v-if="props.spendingByCategory.length"
                        class="flex flex-col gap-4"
                    >
                        <div
                            v-for="cat in props.spendingByCategory"
                            :key="cat.id ?? 'uncategorized'"
                        >
                            <div
                                class="flex items-center justify-between gap-3"
                            >
                                <div class="flex min-w-0 items-center gap-2.5">
                                    <span
                                        class="flex size-8 shrink-0 items-center justify-center rounded-lg text-sm font-medium"
                                        :style="{
                                            backgroundColor: backgroundColor(
                                                cat.color,
                                            ),
                                            color: textColor(cat.color),
                                        }"
                                    >
                                        <component
                                            :is="iconComponent(cat.icon)"
                                            class="size-4"
                                        />
                                    </span>
                                    <span
                                        class="truncate text-sm font-medium text-foreground"
                                    >
                                        {{ cat.name }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <p
                                        class="text-sm font-semibold text-foreground"
                                    >
                                        {{ formattedAmount(cat.total) }}
                                    </p>
                                    <p class="text-xs text-muted-foreground">
                                        {{ cat.percent }}%
                                    </p>
                                </div>
                            </div>
                            <div
                                class="mt-2 h-2 w-full overflow-hidden rounded-full bg-muted"
                            >
                                <div
                                    class="h-full rounded-full"
                                    :style="{
                                        width: `${cat.percent}%`,
                                        backgroundColor: backgroundColor(
                                            cat.color,
                                        ),
                                    }"
                                ></div>
                            </div>
                        </div>
                    </div>
                    <div
                        v-else
                        class="flex flex-col items-center justify-center gap-3 py-12 text-center"
                    >
                        <p class="text-sm text-muted-foreground">
                            No spending yet this month.
                        </p>
                        <Button variant="outline" as-child>
                            <Link :href="createExpense.url()">
                                Add your first expense
                            </Link>
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <Card class="rounded-xl">
                <CardHeader>
                    <CardTitle>Last 6 months</CardTitle>
                    <CardDescription>Monthly spending trend</CardDescription>
                </CardHeader>
                <CardContent>
                    <div
                        v-if="
                            props.monthlySpending.some(
                                (m) => Number(m.total) > 0,
                            )
                        "
                        class="flex h-52 items-end gap-3"
                    >
                        <div
                            v-for="m in props.monthlySpending"
                            :key="m.label"
                            class="flex flex-1 flex-col items-center gap-2"
                        >
                            <span
                                class="text-xs font-semibold text-muted-foreground"
                            >
                                {{
                                    formattedAmount(m.total)
                                        .replace(/\.00$/, '')
                                        .replace(/^[^\d]*/, '$')
                                }}
                            </span>
                            <div class="flex w-full flex-1 items-end">
                                <div
                                    class="w-full rounded-t-lg transition-all"
                                    :style="{
                                        height: `${
                                            (Number(m.total) /
                                                maxMonthlySpending) *
                                            100
                                        }%`,
                                        backgroundColor:
                                            m.label === 'Aug'
                                                ? 'hsl(0 84% 60%)'
                                                : 'hsl(217 91% 55%)',
                                        opacity: 0.85,
                                    }"
                                ></div>
                            </div>
                            <span
                                class="text-xs font-medium text-muted-foreground"
                            >
                                {{ m.label }}
                            </span>
                        </div>
                    </div>
                    <div
                        v-else
                        class="flex flex-col items-center justify-center gap-3 py-12 text-center"
                    >
                        <p class="text-sm text-muted-foreground">
                            No spending history yet.
                        </p>
                        <Button variant="outline" as-child>
                            <Link :href="createExpense.url()">
                                Track your first expense
                            </Link>
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- ===================== Budgets + Recent ===================== -->
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <Card class="rounded-xl">
                <CardHeader>
                    <CardTitle>Budget progress</CardTitle>
                    <CardDescription
                        >Your budget for this month</CardDescription
                    >
                </CardHeader>
                <CardContent>
                    <div
                        v-if="props.budgetProgress.length"
                        class="flex flex-col gap-5"
                    >
                        <div
                            v-for="budget in props.budgetProgress"
                            :key="budget.id"
                        >
                            <div
                                class="flex items-center justify-between gap-3"
                            >
                                <div class="flex min-w-0 items-center gap-2.5">
                                    <span
                                        class="flex size-8 shrink-0 items-center justify-center rounded-lg text-sm font-medium"
                                        :style="{
                                            backgroundColor: backgroundColor(
                                                budget.category?.color ?? null,
                                            ),
                                            color: textColor(
                                                budget.category?.color ?? null,
                                            ),
                                        }"
                                    >
                                        <component
                                            :is="
                                                iconComponent(
                                                    budget.category?.icon ??
                                                        null,
                                                )
                                            "
                                            class="size-4"
                                        />
                                    </span>
                                    <div class="min-w-0">
                                        <p
                                            class="truncate text-sm font-medium text-foreground"
                                        >
                                            {{
                                                budget.category?.name ??
                                                'Uncategorized'
                                            }}
                                        </p>
                                        <p
                                            class="text-xs text-muted-foreground"
                                        >
                                            {{ formattedAmount(budget.spent) }}
                                            of
                                            {{ formattedAmount(budget.amount) }}
                                        </p>
                                    </div>
                                </div>
                                <span
                                    class="shrink-0 text-sm font-semibold"
                                    :class="
                                        budget.percent >= 100
                                            ? 'text-red-500'
                                            : 'text-secondary'
                                    "
                                >
                                    {{ budget.percent }}%
                                </span>
                            </div>
                            <div
                                class="mt-2 h-2 w-full overflow-hidden rounded-full bg-muted"
                            >
                                <div
                                    class="h-full rounded-full transition-all"
                                    :style="{
                                        width: `${Math.min(
                                            budget.percent,
                                            100,
                                        )}%`,
                                        backgroundColor:
                                            budget.percent >= 100
                                                ? 'hsl(0 84% 60%)'
                                                : 'hsl(217 91% 55%)',
                                    }"
                                ></div>
                            </div>
                        </div>
                    </div>
                    <div
                        v-else
                        class="flex flex-col items-center justify-center gap-3 py-12 text-center"
                    >
                        <p class="text-sm text-muted-foreground">
                            No budgets set for this month.
                        </p>
                        <Button variant="outline" as-child>
                            <Link :href="createBudget.url()">
                                Create your first budget
                            </Link>
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <Card class="rounded-xl">
                <CardHeader>
                    <CardTitle>Recent expenses</CardTitle>
                    <CardDescription>Your latest activity</CardDescription>
                </CardHeader>
                <CardContent>
                    <ul
                        v-if="props.recentExpenses.length"
                        class="flex flex-col divide-y divide-border"
                    >
                        <li
                            v-for="expense in props.recentExpenses"
                            :key="expense.id"
                            class="flex items-center justify-between gap-3 py-3 first:pt-0 last:pb-0"
                        >
                            <div class="flex min-w-0 items-center gap-3">
                                <span
                                    class="flex size-9 shrink-0 items-center justify-center rounded-lg text-sm font-medium"
                                    :style="{
                                        backgroundColor: backgroundColor(
                                            expense.category?.color ?? null,
                                        ),
                                        color: textColor(
                                            expense.category?.color ?? null,
                                        ),
                                    }"
                                >
                                    <component
                                        :is="
                                            iconComponent(
                                                expense.category?.icon ?? null,
                                            )
                                        "
                                        class="size-4"
                                    />
                                </span>
                                <div class="min-w-0">
                                    <p
                                        class="truncate text-sm font-medium text-foreground"
                                    >
                                        {{ expense.title }}
                                    </p>
                                    <p class="text-xs text-muted-foreground">
                                        {{
                                            expense.category?.name ??
                                            'Uncategorized'
                                        }}
                                        · {{ formattedDate(expense.date) }} ·
                                        {{ expenseType(expense.type) }}
                                    </p>
                                </div>
                            </div>
                            <span
                                class="shrink-0 text-sm font-semibold text-foreground"
                            >
                                {{ formattedAmount(expense.amount) }}
                            </span>
                        </li>
                    </ul>
                    <div
                        v-else
                        class="flex flex-col items-center justify-center gap-3 py-12 text-center"
                    >
                        <p class="text-sm text-muted-foreground">
                            No expenses yet.
                        </p>
                        <Button variant="outline" as-child>
                            <Link :href="createExpense.url()">
                                Add your first expense
                            </Link>
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </div>
        <!-- ===================== Recurring ===================== -->
        <Card v-if="props.recurringExpenses.length" class="rounded-xl">
            <CardHeader>
                <CardTitle>Recurring expenses</CardTitle>
                <CardDescription>Expenses set to repeat</CardDescription>
            </CardHeader>
            <CardContent>
                <ul class="flex flex-col divide-y divide-border">
                    <li
                        v-for="expense in props.recurringExpenses"
                        :key="expense.id"
                        class="flex items-center justify-between gap-3 py-3 first:pt-0 last:pb-0"
                    >
                        <div class="flex min-w-0 items-center gap-3">
                            <span
                                class="flex size-9 shrink-0 items-center justify-center rounded-lg text-sm font-medium"
                                :style="{
                                    backgroundColor: backgroundColor(
                                        expense.category?.color ?? null,
                                    ),
                                    color: textColor(
                                        expense.category?.color ?? null,
                                    ),
                                }"
                            >
                                <component
                                    :is="
                                        iconComponent(
                                            expense.category?.icon ?? null,
                                        )
                                    "
                                    class="size-4"
                                />
                            </span>
                            <div class="min-w-0">
                                <p
                                    class="truncate text-sm font-medium text-foreground"
                                >
                                    {{ expense.title }}
                                </p>
                                <p class="text-xs text-muted-foreground">
                                    {{
                                        expense.recurring_interval
                                            ? capFirst(
                                                  expense.recurring_interval,
                                              )
                                            : 'Repeats'
                                    }}
                                    ·
                                    {{
                                        formattedDate(
                                            expense.recurring_start_date ?? '',
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                        <span
                            class="shrink-0 text-sm font-semibold text-foreground"
                        >
                            {{ formattedAmount(expense.amount) }}
                        </span>
                    </li>
                </ul>
            </CardContent>
        </Card>
    </div>
</template>
