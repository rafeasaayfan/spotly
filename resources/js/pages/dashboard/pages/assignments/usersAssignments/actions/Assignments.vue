<script setup lang="ts">
import { Button } from '@/components/ui/button';

import Heading from '@/components/headers/Heading.vue';
import DashboardLayout from '@/layouts/DashboardLayout.vue';

import { toast } from '@/lib/sweetAlert';
import { type BreadcrumbItem } from '@/types';

import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import { ArrowBigLeft, PlusCircle, Trash2 } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users assignments',
        href: '/dashboard/assignments/usersAssignments',
    },
];

const props = defineProps<{
    attached: Record<string, any>;
    availableRoles: Record<string, any>;
    availablePermissions: Record<string, any>;
}>();

const type = ref<'roles' | 'permissions'>('roles');

const availableList = computed(() => {
    return type.value === 'roles' ? props.availableRoles : props.availablePermissions;
});

function submit(action: 'delete' | 'add', permissionId: number, type: string, roleId: number) {
    router.post(
        route('dashboard.usersAssignments.storeAssignments', { id: roleId }),
        {
            action: action,
            type: type,
            id: permissionId,
        },
        {
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
        },
    );
}
</script>

<template>
    <Head title="Roles" />

    <DashboardLayout :breadcrumbs="breadcrumbs">
        <div class="w-full p-4">
            <Link :href="route('dashboard.usersAssignments.index')">
                <Button variant="secondary" size="sm">
                    <ArrowBigLeft class="size-4" />
                    <p>Go Back</p>
                </Button>
            </Link>
        </div>

        <div class="rounded-md p-4">
            <Heading :title="'Attach new permission or role for ' + attached.name" description="Attach a new permission or role for this user." />
        </div>

        <div class="flex w-fit gap-2 rounded-md px-4">
            <Button :variant="type === 'roles' ? 'default' : 'outline'" @click="type = 'roles'"> Roles </Button>

            <Button :variant="type === 'permissions' ? 'default' : 'outline'" @click="type = 'permissions'"> Permissions </Button>
        </div>

        <div class="m-4">
            <div class="border-muted grid w-full grid-cols-2 rounded-md border">
                <div class="border-muted col-span-1 flex flex-col border-e">
                    <div class="bg-card rouneded-s-md text-body p-4 text-center font-medium">
                        Attached {{ type.charAt(0).toUpperCase() + type.slice(1) }}
                    </div>

                    <div class="flex flex-col gap-3 p-4">
                        <template v-if="props.attached?.[type]?.length > 0">
                            <div
                                v-for="item in props.attached[type]"
                                :key="item.id"
                                class="relative rounded-md border border-green-300 bg-green-300/90 px-3 py-2 text-center text-sm font-medium text-green-800 transition duration-200 hover:bg-green-300 dark:border-green-700/20 dark:bg-green-900/10 dark:text-green-100 hover:dark:bg-green-900/20"
                            >
                                {{ item.name }}
                                <Button
                                    variant="destructive"
                                    size="icon"
                                    class="absolute end-0 top-0 rounded-s-none hover:scale-101"
                                    @click="submit('delete', item.id, type, props.attached.id)"
                                >
                                    <Trash2 class="size-4.5" />
                                </Button>
                            </div>
                        </template>
                        <div v-else class="bg-muted text-for-bg-muted rounded-md p-2 text-center">No {{ type }} attached</div>
                    </div>
                </div>

                <div class="col-span-1 flex flex-col">
                    <div class="bg-card rouneded-e-md text-body p-4 text-center font-medium">
                        Available {{ type.charAt(0).toUpperCase() + type.slice(1) }}
                    </div>

                    <div class="flex flex-col gap-3 p-4">
                        <template v-if="availableList?.length > 0">
                            <div
                                v-for="item in availableList"
                                :key="item.id"
                                class="relative rounded-md border border-red-300 bg-red-300/90 px-3 py-2 text-center text-sm font-medium text-red-800 transition duration-200 hover:bg-red-300 dark:border-red-700/20 dark:bg-red-900/20 dark:text-red-100 hover:dark:bg-red-900/30"
                            >
                                {{ item.name }}
                                <Button
                                    size="icon"
                                    class="absolute start-0 top-0 rounded-e-none hover:scale-101"
                                    @click="submit('add', item.id, type, props.attached.id)"
                                >
                                    <PlusCircle class="size-4.5" />
                                </Button>
                            </div>
                        </template>
                        <div v-else class="bg-muted text-for-bg-muted rounded-md p-2 text-center">No {{ type }} available</div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
