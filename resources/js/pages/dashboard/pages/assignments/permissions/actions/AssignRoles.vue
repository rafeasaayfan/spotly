<script setup lang="ts">
import { Button } from '@/components/ui/button';

import Heading from '@/components/headers/Heading.vue';
import DashboardLayout from '@/layouts/DashboardLayout.vue';

import { type BreadcrumbItem } from '@/types';
import { toast } from '@/lib/sweetAlert';

import { Head, Link, router } from '@inertiajs/vue3';

import { ArrowBigLeft, PlusCircle, Trash2 } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Assignments',
        href: '/dashboard/assignments/roles',
    },
];

const props = defineProps<{
    attachedRoles: Record<string, any>;
    availableRoles: Record<string, any>;
}>();


function submit(action: 'delete' | 'add', permissionId: number, roleId: number) {
    router.post(route('dashboard.roles.storeAssignments', { id: roleId }), {
        action: action,
        id: permissionId,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            toast.fire({
                icon: 'success',
                title: action === 'delete' ? 'Permission detached successfully' : 'Permission attached successfully',
            });
        },
        onError: (error) => {
            toast.fire({
                icon: 'error',
                title: error,
            });
        },
    });
}
</script>

<template>
    <Head title="Roles" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <div class="w-full p-4">
            <Link :href="route('dashboard.permissions.index')">
                <Button variant="secondary" size="sm">
                    <ArrowBigLeft class="size-4" />
                    <p>Go Back</p>
                </Button>
            </Link>
        </div>

        <div class="rounded-md p-4">
            <Heading :title="'Attach new role for ' + attachedRoles.name" description="Attach a new role for this permission." />
        </div>

        <div class="m-4">
            <div class="border-muted grid w-full grid-cols-2 rounded-md border">
                <div class="border-muted col-span-1 flex flex-col border-e">
                    <div class="bg-card rouneded-s-md text-body p-4 text-center font-medium">Attached Roles</div>

                    <div class="flex flex-col gap-3 px-3 py-4 md:px-8">
                        <template v-if="props.attachedRoles?.roles?.length > 0">
                            <div
                                v-for="role in props.attachedRoles.roles"
                                :key="role.id"
                                class="relative rounded-md border border-green-300 bg-green-300/90 px-3 py-2 text-center text-sm font-medium text-green-800 transition duration-200 hover:bg-green-300 dark:border-green-700/20 dark:bg-green-900/10 dark:text-green-100 hover:dark:bg-green-900/20"
                            >
                                {{ role.name }}

                                <Button variant="destructive" size="icon" class="absolute end-0 top-0 hover:scale-101 rounded-s-none"
                                    @click="submit('delete', role.id, props.attachedRoles.id)">
                                    <Trash2 class="size-4.5" />
                                </Button>
                            </div>
                        </template>

                        <div v-else class="bg-muted text-for-bg-muted rounded-md p-2 text-center">No roles attached</div>
                    </div>
                </div>

                <div class="col-span-1 flex flex-col">
                    <div class="bg-card rouneded-e-md text-body p-4 text-center font-medium">Available Roles</div>

                    <div class="flex flex-col gap-3 px-3 py-4 md:px-8">
                        <template v-if="props.availableRoles?.length > 0">
                            <div
                                v-for="role in props.availableRoles"
                                :key="role.id"
                                class="relative rounded-md border border-red-300 bg-red-300/90 px-3 py-2 text-center text-sm font-medium text-red-800 transition duration-200 hover:bg-red-300 dark:border-red-700/20 dark:bg-red-900/20 dark:text-red-100 hover:dark:bg-red-900/30"
                            >
                                {{ role.name }}

                                <Button size="icon" class="absolute start-0 top-0 hover:scale-101 rounded-e-none"
                                    @click="submit('add', role.id, props.attachedRoles.id)">
                                    <PlusCircle class="size-4.5" />
                                </Button>
                            </div>
                        </template>
                        <div v-else class="bg-muted text-for-bg-muted rounded-md p-2 text-center">No roles availabe</div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
