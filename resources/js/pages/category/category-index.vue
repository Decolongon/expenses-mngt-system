<script setup lang="ts">
import { Head, Link, Form, usePage } from '@inertiajs/vue3';
import { icons as lucideIcons } from '@lucide/vue';
import { destroy, create, edit, index } from "@/actions/App/Http/Controllers/CategoryController";
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
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
                title: 'Categories',
                href: index(),
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

const DEFAULT_COLOR = '#e2e8f0';
const DEFAULT_ICON = 'CircleDollarSign';

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

const page = usePage();
</script>

<template>

    <Head title="Categories" />
    <div class="flex h-full flex-1 flex-col gap-4 p-4">
        <Card>
            <CardHeader>
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <CardTitle>Categories</CardTitle>
                        <CardDescription>
                            Manage the categories used to organize your expenses.
                        </CardDescription>
                    </div>
                    <Button variant="outline" as-child>
                        <Link :href="create.url()">Create Category</Link>
                    </Button>
                </div>
            </CardHeader>
            <CardContent>
                <div v-if="props.categories.length" class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b text-left text-muted-foreground">
                                <th class="px-4 py-3 font-medium">Name</th>
                                <th class="px-4 py-3 text-right font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="category in props.categories"
                                :key="category.id"
                                class="border-b last:border-0"
                            >
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="inline-flex size-10 shrink-0 items-center justify-center rounded-xl"
                                            :style="{
                                                backgroundColor: backgroundColor(category.color),
                                                color: textColor(category.color),
                                            }"
                                        >
                                            <component
                                                :is="iconComponent(category.icon)"
                                                :size="20"
                                            />
                                        </span>
                                        <span class="font-medium">{{ category.name }}</span>
                                    </div>
                                </td>
<td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <Button variant="outline" size="sm" as-child>
                                            <Link :href="edit.url(category)">Edit</Link>
                                        </Button>

                                        <Dialog>
                                            <DialogTrigger as-child>
                                                <Button variant="destructive" size="sm">
                                                    Delete
                                                </Button>
                                            </DialogTrigger>
                                            <DialogContent>
                                                <Form
                                                    :action="destroy(category)"
                                                    method="delete"
                                                    :options="{ preserveScroll: true }"
                                                    class="space-y-6"
                                                    v-slot="{ processing }"
                                                >
                                                    <DialogHeader class="space-y-3">
                                                        <DialogTitle>
                                                            Delete "{{ category.name }}"?
                                                        </DialogTitle>
                                                        <DialogDescription>
                                                            This will permanently delete the
                                                            category. This action cannot be undone.
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
                                                            :disabled="processing"
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

                <div v-else class="py-12 text-center text-sm text-muted-foreground">
                    No categories yet.
                </div>
            </CardContent>
        </Card>
    </div>

</template>
