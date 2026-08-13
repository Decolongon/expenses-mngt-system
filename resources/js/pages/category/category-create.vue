<script setup lang="ts">
import { Head, Form } from '@inertiajs/vue3';
import { Check, icons as lucideIcons } from '@lucide/vue';
import { computed, ref } from 'vue';
import { index, store } from "@/actions/App/Http/Controllers/CategoryController";
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Categories',
                href: index(),
            },
        ],
    },
});

const props = defineProps<{
    colors: Array<{ value: string; label: string }>;
    icons: Array<{ value: string; label: string }>;
}>();

const categoryName = ref<string>('');
const selectedColor = ref<string>('');
const selectedIcon = ref<string>('');

const selectedIconComponent = computed(() =>
    selectedIcon.value
        ? (lucideIcons as Record<string, any>)[selectedIcon.value]
        : null,
);

const selectedColorLabel = computed(() =>
    props.colors.find((color) => color.value === selectedColor.value)?.label ?? null,
);

const selectedIconLabel = computed(() =>
    props.icons.find((icon) => icon.value === selectedIcon.value)?.label ?? null,
);

function iconComponent(name: string): any {
    return (lucideIcons as Record<string, any>)[name];
}

function previewBackground(): string {
    return selectedColor.value || '#e2e8f0';
}

function previewTextColor(): string {
    if (!selectedColor.value) {
        return '#334155';
    }

    const hex = selectedColor.value.replace('#', '');

    const r = parseInt(hex.substring(0, 2), 16);
    const g = parseInt(hex.substring(2, 4), 16);
    const b = parseInt(hex.substring(4, 6), 16);

    const luminance = 0.299 * r + 0.587 * g + 0.114 * b;

    return luminance > 150 ? '#111827' : '#ffffff';
}

function previewAccent(): string {
    return 'rgba(255,255,255,0.35)';
}
</script>

<template>

    <Head title="Create category" />

    <Form :action="store()" #default="{
        errors,
    }" resetOnSuccess>

        <div class="grid gap-6">
            <Card>
                <CardHeader>
                    <CardTitle>Preview</CardTitle>
                    <CardDescription>
                        This is how your category will look.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div
                        class="flex items-center gap-4 rounded-xl border p-5 shadow-sm transition-colors duration-200"
                        :style="{
                            backgroundColor: previewBackground(),
                            color: previewTextColor(),
                        }"
                    >
                        <span
                            class="inline-flex size-14 shrink-0 items-center justify-center rounded-2xl"
                            :style="{ backgroundColor: previewAccent() }"
                        >
                            <component
                                :is="selectedIconComponent"
                                v-if="selectedIconComponent"
                                :size="28"
                            />
                            <component v-else :is="iconComponent('Tag')" :size="28" />
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-lg font-semibold">
                                {{ categoryName || 'Your category name' }}
                            </p>
                            <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm opacity-90">
                                <span
                                    v-if="selectedColorLabel"
                                    class="inline-flex items-center gap-1.5"
                                >
                                    <span
                                        class="inline-block size-2.5 rounded-full"
                                        :style="{ backgroundColor: previewTextColor() }"
                                    ></span>
                                    {{ selectedColorLabel }}
                                </span>
                                <span v-else>No color selected</span>
                                <template v-if="selectedIconLabel">
                                    <span>· {{ selectedIconLabel }}</span>
                                </template>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <div class="grid gap-2">
                <Label for="category-name">Name</Label>
                <Input
                    id="category-name"
                    v-model="categoryName"
                    type="text"
                    name="name"
                    placeholder="e.g. Groceries"
                />
                <InputError :message="errors.name" />
            </div>

            <div class="grid gap-3">
                <Label>Color</Label>
                <input type="hidden" name="color" :value="selectedColor" />
                <div class="grid grid-cols-5 gap-2 sm:grid-cols-10">
                    <button
                        v-for="color in props.colors"
                        :key="color.value"
                        type="button"
                        class="relative flex aspect-square items-center justify-center rounded-md border transition-all hover:scale-105"
                        :style="{ backgroundColor: color.value }"
                        :class="selectedColor === color.value
                            ? 'ring-2 ring-offset-2 ring-primary'
                            : 'border-border'"
                        :title="color.value"
                        @click="selectedColor = color.value"
                    >
                        <Check
                            v-if="selectedColor === color.value"
                            class="size-4 text-white drop-shadow"
                        />
                        <span
                            class="pointer-events-none absolute -bottom-5 left-1/2 -translate-x-1/2 whitespace-nowrap text-[10px] text-muted-foreground"
                        >
                            {{ color.label }}
                        </span>
                    </button>
                </div>
            </div>

            <div class="grid gap-3 pt-4">
                <Label>Icon</Label>
                <input type="hidden" name="icon" :value="selectedIcon" />
                <div class="grid grid-cols-5 gap-2 sm:grid-cols-10">
                    <button
                        v-for="icon in props.icons"
                        :key="icon.value"
                        type="button"
                        class="flex aspect-square flex-col items-center justify-center gap-1 rounded-md border bg-background p-1 transition-all hover:scale-105"
                        :class="selectedIcon === icon.value
                            ? 'border-primary bg-primary/10 ring-2 ring-primary'
                            : 'border-border'"
                        :title="icon.label"
                        @click="selectedIcon = icon.value"
                    >
                        <component :is="iconComponent(icon.value)" class="size-5" />
                        <span class="max-w-full truncate px-0.5 text-[10px] text-muted-foreground">
                            {{ icon.label }}
                        </span>
                    </button>
                </div>
            </div>

            <Button type="submit" class="mt-2 w-full sm:w-auto">
                Create Category
            </Button>
        </div>
    </Form>

</template>
