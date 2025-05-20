<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { File, Input, SelectWithSearch } from '@/components/ui/fields';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowBigLeft } from 'lucide-vue-next';

const props = defineProps<{
    users: Array<{ id: number; name: string }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Restaurants',
        href: '/dashboard/restaurants',
    },
];

function submit() {
    form.post(route(`dashboard.restaurants.store`));
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
};

const form = useForm<Record<string, string | File | number | null>>(Object.fromEntries(Object.keys(columns).map((key) => [key, ''])));
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

        <div class="m-4 flex flex-col gap-3 rounded-md border bg-zinc-950 px-8 py-4 shadow-md">
            <h3>Create new restaurants</h3>

            <form class="grid gap-4 lg:grid-cols-2 xl:grid-cols-3" @submit.prevent="submit">
                <div v-for="(config, field) in columns" :key="field">
                    <label class="mb-1 block font-medium">{{ config.label }}</label>

                    <template v-if="config.type === 'text' || config.type === 'email' || config.type === 'password'">
                        <Input :type="config.type" v-model="form[field]" class="input" />
                    </template>

                    <template v-else-if="config.type === 'textarea'">
                        <textarea />
                    </template>

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

                    <template v-else-if="config.type === 'image'">
                        <File
                            @change="
                                (e: Event) => {
                                    const target = e.target as HTMLInputElement;
                                    form[field] = target?.files?.[0] ?? null;
                                }
                            "
                        />
                    </template>

                    <template v-else-if="config.type === 'time'">
                        <Input type="time" v-model="form[field]" />
                    </template>

                    <div v-if="form.errors[field]" class="mt-1 text-sm text-red-500">
                        {{ form.errors[field] }}
                    </div>
                </div>

                <div class="flex justify-end lg:col-span-2 xl:col-span-3">
                    <Button :disabled="form.processing">Submit</Button>
                </div>
            </form>
        </div>
    </DashboardLayout>
</template>
