<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { File, Input, SelectWithSearch } from '@/components/ui/fields';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowBigLeft } from 'lucide-vue-next';

const props = defineProps<{
    data: Record<string, any>;
    users: Array<{ id: number; name: string }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Restaurants',
        href: '/dashboard/restaurants',
    },
];

const form = useForm({ ...props.data });

function submit() {
    form.put(route('dashboard.restaurants.update', props.data.id));
}

const columns = {
    name: {
        type: 'text',
        label: 'Name',
    },
    user_id: {
        type: 'select',
        label: 'Owner',
    },
    image: {
        type: 'image',
        label: 'Image',
    },
    country: {
        type: 'text',
        label: 'Country',
    },
    city: {
        type: 'text',
        label: 'City',
    },
    phone_number: {
        type: 'text',
        label: 'Phone Number',
    },
    email: {
        type: 'email',
        label: 'Email',
    },
    open_start: {
        type: 'time',
        label: 'Opening hours',
    },
    close_start: {
        type: 'time',
        label: 'Closing hours',
    },
    status: {
        type: 'select',
        label: 'Status',
    },
};
</script>

<template>
    <Head title="Restaurants" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <div class="w-full p-4">
            <Link
                :href="route('dashboard.restaurants.index')"
                class="flex w-fit cursor-pointer items-center gap-1 rounded bg-blue-600/30 px-2 py-1 hover:bg-blue-600/50"
            >
                <ArrowBigLeft class="size-4" />
                <p class="text-semibold text-sm">Go Back</p>
            </Link>
        </div>

        <div class="mx-4 flex flex-col gap-3 bg-card rounded-md border border-muted p-5 shadow-md">
            <h3>Edit restaurant data</h3>

            <form @submit.prevent="submit" class="grid gap-4 lg:grid-cols-2 xl:grid-cols-3">
                <div v-for="(config, field) in columns" :key="field">
                    <label class="mb-1 block font-medium text-white">{{ config.label }}</label>

                    <!-- Text / Email -->
                    <template v-if="config.type === 'text' || config.type === 'email'">
                        <Input :type="config.type" v-model="form[field]" />
                    </template>

                    <!-- Textarea -->
                    <template v-else-if="config.type === 'textarea'">
                        <textarea class="w-full rounded-md border bg-zinc-800 p-2 text-white" />
                    </template>

                    <!-- Select -->
                    <template v-else-if="config.type === 'select'">
                        <SelectWithSearch
                            v-model="form[field]"
                            :options="
                                props.users.map((user) => ({
                                    label: user.name ?? user,
                                    value: user.id ?? user,
                                }))
                            "
                            placeholder="Select a user"
                        />
                    </template>

                    <!-- Image -->
                    <template v-else-if="config.type === 'image'">
                        <File
                            :src="form[field] ? form[field] : '/images/default-image.avif'"
                            @change="
                                (e: Event) => {
                                    const target = e.target as HTMLInputElement;
                                    form[field] = target?.files?.[0] ?? null;
                                }
                            "
                        />
                    </template>

                    <div v-if="form.errors[field]" class="mt-1 text-sm text-red-500">
                        {{ form.errors[field] }}
                    </div>
                </div>

                <div class="flex justify-end lg:col-span-2 xl:col-span-3">
                    <Button :disabled="form.processing">Update</Button>
                </div>
            </form>
        </div>
    </DashboardLayout>
</template>
