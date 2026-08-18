<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
    ArrowRight,
    Check,
    ChevronDown,
    Menu,
    Moon,
    PiggyBank,
    PieChart,
    Plus,
    Receipt,
    ShieldCheck,
    Sparkles,
    Sun,
    Tags,
    Target,
    TrendingDown,
    Wallet,
    X,
} from '@lucide/vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { useAppearance } from '@/composables/useAppearance';
import { dashboard, home, login, register } from '@/routes';

const appName = 'Expense Manager';

const { resolvedAppearance, updateAppearance } = useAppearance();

const mobileMenuOpen = ref(false);

const toggleTheme = () =>
    updateAppearance(resolvedAppearance.value === 'dark' ? 'light' : 'dark');

const navLinks = [
    { label: 'Features', href: '#features' },
    { label: 'How it works', href: '#how-it-works' },
];

const features = [
    {
        icon: Wallet,
        title: 'Track every expense',
        description:
            'Log purchases in seconds and keep a complete, organized record of everything you spend.',
    },
    {
        icon: PiggyBank,
        title: 'Smart budgets',
        description:
            'Set monthly budgets per category and get instant alerts before you go over the limit.',
    },
    {
        icon: Tags,
        title: 'Tidy categories',
        description:
            'Group your spending into clear categories so you always know exactly where money goes.',
    },
    {
        icon: PieChart,
        title: 'Clear insights',
        description:
            'Beautiful charts and reports turn raw numbers into decisions you can act on.',
    },
    {
        icon: Target,
        title: 'Reach your goals',
        description:
            'Build lasting saving habits with measurable targets and steady progress tracking.',
    },
    {
        icon: ShieldCheck,
        title: 'Private & secure',
        description:
            'Your financial data stays protected and only ever visible to you and your account.',
    },
];
const steps = [
    {
        icon: Plus,
        title: 'Create an account',
        description:
            'Sign up in seconds — no card required. Your workspace is ready right away.',
    },
    {
        icon: Receipt,
        title: 'Add your expenses',
        description:
            'Record purchases quickly and drop them into the category that fits best.',
    },
    {
        icon: TrendingDown,
        title: 'Watch your savings grow',
        description:
            'Review insights to cut waste, stay inside your budgets, and grow your balance.',
    },
];

const checkmarks = [
    'Unlimited expenses & categories',
    'Real-time budget alerts',
    'Responsive on every device',
    'Light & dark mode included',
];

const benefits = [
    'No credit card required',
    'Set up in minutes',
    'Cancel anytime',
];
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div
        class="flex min-h-screen flex-col bg-background text-foreground antialiased"
    >
        <!-- ===================== Navigation ===================== -->
        <header
            class="sticky top-0 z-40 border-b border-border/70 bg-white/80 backdrop-blur-md dark:bg-[hsl(222_47%_6%/0.8)]"
        >
            <nav
                class="mx-auto flex h-16 w-full max-w-6xl items-center justify-between px-4 sm:px-6"
            >
                <Link :href="home()" class="flex items-center gap-2.5">
                    <span
                        class="flex size-9 items-center justify-center rounded-xl bg-secondary text-secondary-foreground shadow-sm"
                    >
                        <AppLogoIcon class="size-5 fill-current text-white" />
                    </span>
                    <span
                        class="text-lg font-semibold tracking-tight text-foreground"
                    >
                        {{ appName }}
                    </span>
                </Link>

                <div class="hidden items-center gap-8 md:flex">
                    <a
                        v-for="link in navLinks"
                        :key="link.href"
                        :href="link.href"
                        class="text-sm font-medium text-muted-foreground transition-colors hover:text-foreground"
                    >
                        {{ link.label }}
                    </a>
                </div>

                <div class="flex items-center gap-2 sm:gap-3">
                    <button
                        type="button"
                        @click="toggleTheme"
                        :aria-label="
                            resolvedAppearance === 'dark'
                                ? 'Switch to light mode'
                                : 'Switch to dark mode'
                        "
                        class="flex size-9 items-center justify-center rounded-lg border border-border bg-white text-foreground transition-colors dark:bg-transparent"
                    >
                        <Sun
                            :class="[
                                'size-4',
                                resolvedAppearance === 'dark' ? 'hidden' : '',
                            ]"
                            aria-hidden="true"
                        />
                        <Moon
                            :class="[
                                'size-4',
                                resolvedAppearance !== 'dark' ? 'hidden' : '',
                            ]"
                            aria-hidden="true"
                        />
                    </button>

                    <template v-if="$page.props.auth.user">
                        <Link
                            :href="dashboard()"
                            class="hidden items-center rounded-lg bg-secondary px-4 py-2 text-sm font-medium text-secondary-foreground shadow-sm transition-colors hover:bg-secondary/90 sm:inline-flex"
                        >
                            Dashboard
                            <ArrowRight class="size-4" />
                        </Link>
                    </template>
                    <template v-else>
                        <Link
                            :href="login()"
                            class="hidden items-center rounded-lg border border-border bg-white px-4 py-2 text-sm font-medium text-foreground transition-colors hover:bg-accent sm:inline-flex dark:bg-transparent"
                        >
                            Log in
                        </Link>
                        <Link
                            :href="register()"
                            class="hidden items-center rounded-lg bg-secondary px-4 py-2 text-sm font-medium text-secondary-foreground shadow-sm transition-colors hover:bg-secondary/90 sm:inline-flex"
                        >
                            Get started
                        </Link>
                    </template>

                    <button
                        type="button"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="flex size-9 items-center justify-center rounded-lg border border-border bg-white text-foreground md:hidden dark:bg-transparent"
                        aria-label="Toggle menu"
                    >
                        <Menu v-if="!mobileMenuOpen" class="size-5" />
                        <X v-else class="size-5" />
                    </button>
                </div>
            </nav>

            <!-- Mobile menu -->
            <div
                v-if="mobileMenuOpen"
                class="border-t border-border bg-white px-4 py-4 md:hidden dark:bg-[hsl(222_47%_7%)]"
            >
                <div class="flex flex-col gap-3">
                    <a
                        v-for="link in navLinks"
                        :key="link.href"
                        :href="link.href"
                        @click="mobileMenuOpen = false"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-muted-foreground transition-colors hover:bg-accent hover:text-foreground"
                    >
                        {{ link.label }}
                    </a>
                    <div
                        class="mt-2 flex flex-col gap-2 border-t border-border pt-4"
                    >
                        <template v-if="$page.props.auth.user">
                            <Link
                                :href="dashboard()"
                                @click="mobileMenuOpen = false"
                                class="inline-flex items-center justify-center rounded-lg bg-secondary px-4 py-2.5 text-sm font-medium text-secondary-foreground"
                            >
                                Dashboard
                                <ArrowRight class="ml-1 size-4" />
                            </Link>
                        </template>
                        <template v-else>
                            <Link
                                :href="login()"
                                @click="mobileMenuOpen = false"
                                class="inline-flex items-center justify-center rounded-lg border border-border bg-white px-4 py-2.5 text-sm font-medium text-foreground dark:bg-transparent"
                            >
                                Log in
                            </Link>
                            <Link
                                :href="register()"
                                @click="mobileMenuOpen = false"
                                class="inline-flex items-center justify-center rounded-lg bg-secondary px-4 py-2.5 text-sm font-medium text-secondary-foreground"
                            >
                                Get started
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </header>

        <!-- ===================== Hero ===================== -->
        <main class="flex-1">
            <section
                class="mx-auto grid w-full max-w-6xl items-center gap-12 px-4 pt-14 pb-16 sm:px-6 lg:grid-cols-2 lg:pt-20 lg:pb-24"
            >
                <div>
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-3 py-1 text-xs font-semibold text-red-600 dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-400"
                    >
                        <Sparkles class="size-3.5" />
                        Simple expense management
                    </span>

                    <h1
                        class="mt-5 text-4xl leading-tight font-bold tracking-tight sm:text-5xl lg:text-[3.4rem]"
                    >
                        Take control of
                        <span class="text-secondary">every dollar</span>
                        <span class="relative inline-block">
                            you spend
                            <span
                                class="absolute -bottom-1 left-0 -z-10 h-1.5 w-full rounded-full bg-red-500/80"
                            ></span>
                        </span>
                    </h1>

                    <p
                        class="mt-6 max-w-xl text-lg leading-relaxed text-muted-foreground"
                    >
                        {{ appName }} makes tracking expenses, setting budgets,
                        and saving money effortless — all in one clean, friendly
                        dashboard built for real life.
                    </p>

                    <div
                        class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center"
                    >
                        <Link
                            :href="register()"
                            class="inline-flex items-center justify-center rounded-xl bg-secondary px-6 py-3.5 text-base font-semibold text-secondary-foreground shadow-lg shadow-blue-600/20 transition-all hover:bg-secondary/90"
                        >
                            Get started free
                            <ArrowRight class="ml-2 size-5" />
                        </Link>
                        <a
                            href="#features"
                            class="inline-flex items-center justify-center rounded-xl border border-border bg-white px-6 py-3.5 text-base font-semibold text-foreground transition-colors hover:bg-accent dark:bg-transparent"
                        >
                            Explore features
                        </a>
                    </div>

                    <ul
                        class="mt-8 grid max-w-lg grid-cols-1 gap-2.5 sm:grid-cols-2"
                    >
                        <li
                            v-for="item in checkmarks"
                            :key="item"
                            class="flex items-center gap-2.5 text-sm text-muted-foreground"
                        >
                            <span
                                class="flex size-5 shrink-0 items-center justify-center rounded-full bg-secondary/15 text-secondary dark:bg-secondary/25 dark:text-[hsl(217_91%_70%)]"
                            >
                                <Check class="size-3.5" />
                            </span>
                            {{ item }}
                        </li>
                    </ul>
                </div>

                <!-- Dashboard mockup (blue secondary panel = 30%) -->
                <div class="relative">
                    <div
                        class="pointer-events-none absolute -inset-6 -z-10 rounded-[2rem] bg-gradient-to-br from-secondary/20 via-transparent to-red-500/10 blur-2xl"
                    ></div>
                    <div
                        class="overflow-hidden rounded-2xl border border-border bg-card shadow-2xl dark:border-border/70"
                    >
                        <div
                            class="flex items-center justify-between border-b border-border/70 bg-secondary px-5 py-4 text-secondary-foreground dark:bg-secondary"
                        >
                            <div class="flex items-center gap-1.5">
                                <span
                                    class="size-2.5 rounded-full bg-white/50"
                                ></span>
                                <span
                                    class="size-2.5 rounded-full bg-white/50"
                                ></span>
                                <span
                                    class="size-2.5 rounded-full bg-white/50"
                                ></span>
                            </div>
                            <span class="text-sm font-semibold"
                                >August overview</span
                            >
                            <span
                                class="rounded-full bg-white/20 px-2 py-0.5 text-xs font-semibold"
                            >
                                Live
                            </span>
                        </div>

                        <div class="grid grid-cols-3 gap-3 p-4">
                            <div
                                class="rounded-xl bg-white p-3 shadow-sm dark:bg-[hsl(223_47%_12%)]"
                            >
                                <p
                                    class="text-xs font-medium text-muted-foreground"
                                >
                                    Spent
                                </p>
                                <p
                                    class="mt-1 text-lg font-bold text-foreground"
                                >
                                    $1,280
                                </p>
                                <p
                                    class="mt-0.5 text-xs font-semibold text-red-500"
                                >
                                    ▲ 12% vs last month
                                </p>
                            </div>
                            <div
                                class="rounded-xl bg-white p-3 shadow-sm dark:bg-[hsl(223_47%_12%)]"
                            >
                                <p
                                    class="text-xs font-medium text-muted-foreground"
                                >
                                    Budget
                                </p>
                                <p
                                    class="mt-1 text-lg font-bold text-foreground"
                                >
                                    $1,800
                                </p>
                                <p
                                    class="mt-0.5 text-xs font-semibold text-emerald-500"
                                >
                                    on track
                                </p>
                            </div>
                            <div
                                class="rounded-xl bg-white p-3 shadow-sm dark:bg-[hsl(223_47%_12%)]"
                            >
                                <p
                                    class="text-xs font-medium text-muted-foreground"
                                >
                                    Left
                                </p>
                                <p
                                    class="mt-1 text-lg font-bold text-foreground"
                                >
                                    $520
                                </p>
                                <p
                                    class="mt-0.5 text-xs font-semibold text-secondary"
                                >
                                    29% remaining
                                </p>
                            </div>
                        </div>

                        <div class="px-4 pb-4">
                            <div
                                class="rounded-xl bg-white p-4 shadow-sm dark:bg-[hsl(223_47%_12%)]"
                            >
                                <div class="flex items-center justify-between">
                                    <p
                                        class="text-sm font-semibold text-foreground"
                                    >
                                        Monthly spending
                                    </p>
                                    <span
                                        class="rounded-full bg-red-50 px-2 py-0.5 text-[11px] font-semibold text-red-600 dark:bg-red-500/15 dark:text-red-400"
                                    >
                                        ▼ 8%
                                    </span>
                                </div>
                                <div class="mt-4 flex h-28 items-end gap-2">
                                    <div
                                        class="flex-1 rounded-t bg-secondary/30 dark:bg-secondary/40"
                                        style="height: 40%"
                                    ></div>
                                    <div
                                        class="flex-1 rounded-t bg-secondary/40 dark:bg-secondary/50"
                                        style="height: 60%"
                                    ></div>
                                    <div
                                        class="flex-1 rounded-t bg-secondary/50 dark:bg-secondary/60"
                                        style="height: 45%"
                                    ></div>
                                    <div
                                        class="flex-1 rounded-t bg-secondary/60 dark:bg-secondary/70"
                                        style="height: 75%"
                                    ></div>
                                    <div
                                        class="flex-1 rounded-t bg-secondary/70 dark:bg-secondary/80"
                                        style="height: 55%"
                                    ></div>
                                    <div
                                        class="flex-1 rounded-t bg-secondary/80 dark:bg-secondary/90"
                                        style="height: 85%"
                                    ></div>
                                    <div
                                        class="flex-1 rounded-t bg-red-500"
                                        style="height: 95%"
                                    ></div>
                                </div>
                                <div
                                    class="mt-2 flex justify-between text-[11px] font-medium text-muted-foreground"
                                >
                                    <span>Mon</span><span>Tue</span
                                    ><span>Wed</span><span>Thu</span
                                    ><span>Fri</span><span>Sat</span
                                    ><span>Sun</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ===================== Benefits strip ===================== -->
            <section
                class="border-y border-border/70 bg-white dark:bg-[hsl(222_47%_7%)]"
            >
                <div
                    class="mx-auto grid w-full max-w-6xl grid-cols-1 gap-4 px-4 py-10 sm:grid-cols-3 sm:px-6"
                >
                    <div
                        v-for="benefit in benefits"
                        :key="benefit"
                        class="flex items-center justify-center gap-2.5"
                    >
                        <span
                            class="flex size-6 shrink-0 items-center justify-center rounded-full bg-secondary/15 text-secondary dark:bg-secondary/25 dark:text-[hsl(217_91%_70%)]"
                        >
                            <Check class="size-3.5" />
                        </span>
                        <span class="text-sm font-medium text-foreground">
                            {{ benefit }}
                        </span>
                    </div>
                </div>
            </section>

            <!-- ===================== Features ===================== -->
            <section
                id="features"
                class="mx-auto w-full max-w-6xl scroll-mt-20 px-4 py-16 sm:px-6 lg:py-24"
            >
                <div class="mx-auto max-w-2xl text-center">
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-semibold text-secondary dark:border-blue-500/30 dark:bg-blue-500/10"
                    >
                        <Target class="size-3.5" />
                        Features
                    </span>
                    <h2
                        class="mt-4 text-3xl font-bold tracking-tight sm:text-4xl"
                    >
                        Everything you need to
                        <span class="text-secondary">manage money</span> better
                    </h2>
                    <p class="mt-4 text-lg text-muted-foreground">
                        Thoughtfully designed tools that make tracking your
                        finances feel simple and satisfying.
                    </p>
                </div>

                <div
                    class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3"
                >
                    <div
                        v-for="feature in features"
                        :key="feature.title"
                        class="group rounded-2xl border border-border bg-card p-6 shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg dark:border-border/70"
                    >
                        <div
                            class="flex size-11 items-center justify-center rounded-xl bg-secondary text-secondary-foreground shadow-sm transition-colors group-hover:bg-secondary/90"
                        >
                            <component :is="feature.icon" class="size-5" />
                        </div>
                        <span
                            class="mt-4 block h-1 w-8 rounded-full bg-red-500 transition-all group-hover:w-12"
                        ></span>
                        <h3 class="mt-3 text-lg font-semibold text-foreground">
                            {{ feature.title }}
                        </h3>
                        <p
                            class="mt-2 text-sm leading-relaxed text-muted-foreground"
                        >
                            {{ feature.description }}
                        </p>
                    </div>
                </div>
            </section>

            <!-- ===================== How it works (blue band = 30%) ===================== -->
            <section
                id="how-it-works"
                class="scroll-mt-20 bg-secondary py-16 text-secondary-foreground lg:py-24"
            >
                <div class="mx-auto w-full max-w-6xl px-4 sm:px-6">
                    <div class="max-w-2xl">
                        <span
                            class="inline-flex items-center gap-2 rounded-full bg-white/20 px-3 py-1 text-xs font-semibold"
                        >
                            <ChevronDown class="size-3.5 -rotate-90" />
                            How it works
                        </span>
                        <h2
                            class="mt-4 text-3xl font-bold tracking-tight sm:text-4xl"
                        >
                            Up and running in three easy steps
                        </h2>
                        <p class="mt-4 text-lg leading-relaxed text-white/80">
                            From your first sign-up to your first saved dollar,
                            the whole journey takes minutes.
                        </p>
                    </div>

                    <div class="mt-12 grid grid-cols-1 gap-8 md:grid-cols-3">
                        <div
                            v-for="(step, index) in steps"
                            :key="step.title"
                            class="rounded-2xl bg-white/10 p-6 backdrop-blur-sm transition-colors hover:bg-white/15"
                        >
                            <div class="flex items-center justify-between">
                                <span
                                    class="flex size-11 items-center justify-center rounded-xl bg-white text-secondary"
                                >
                                    <component :is="step.icon" class="size-5" />
                                </span>
                                <span class="text-4xl font-bold text-white/30">
                                    {{ `0${index + 1}` }}
                                </span>
                            </div>
                            <h3 class="mt-5 text-lg font-semibold">
                                {{ step.title }}
                            </h3>
                            <p
                                class="mt-2 text-sm leading-relaxed text-white/80"
                            >
                                {{ step.description }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- ===================== Footer ===================== -->
        <footer
            class="border-t border-border/70 bg-white dark:bg-[hsl(222_47%_7%)]"
        >
            <div
                class="mx-auto flex w-full max-w-6xl flex-col items-center justify-between gap-4 px-4 py-8 sm:flex-row sm:px-6"
            >
                <div class="flex items-center gap-2">
                    <span
                        class="flex size-7 items-center justify-center rounded-lg bg-secondary text-secondary-foreground"
                    >
                        <AppLogoIcon class="size-4 fill-current text-white" />
                    </span>
                    <span class="text-sm font-semibold text-foreground">
                        {{ appName }}
                    </span>
                </div>
                <p class="text-sm text-muted-foreground">
                    © {{ new Date().getFullYear() }} {{ appName }}. Made with
                    care.
                </p>
            </div>
        </footer>
    </div>
</template>
