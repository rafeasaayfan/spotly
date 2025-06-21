<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { Inbox, PlusCircle, Trash2 } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
    attachedRoles: Record<string, any>;
    availableRoles: Record<string, any>;
}>();

const attachedRoles = ref<Record<string, any>>(props.attachedRoles);
const availableRoles = ref<Record<string, any>>(props.availableRoles);

watch(
    () => props.attachedRoles,
    (newVal) => {
        attachedRoles.value = newVal;
    },
    { immediate: true },
);

watch(
    () => props.availableRoles,
    (newVal) => {
        availableRoles.value = newVal;
    },
    { immediate: true },
);

const attachedRoleList = computed(() => attachedRoles.value ?? []);
const availableRoleList = computed(() => availableRoles.value ?? []);

function submit(action: 'delete' | 'add', roleId: number, permissionId: number) {
    router.post(
        route('dashboard.permissions.storeAssignments', { id: permissionId }),
        {
            action: action,
            id: roleId,
        },
        {
            async onSuccess() {
                try {
                    const response = await axios.get(route('dashboard.permissions.assignRoles', permissionId));
                    attachedRoles.value = response.data.attachedRoles;
                    availableRoles.value = response.data.availableRoles;
                } catch (error) {
                    console.error('Failed to fetch record:', error);
                }
            },
        },
    );
}
</script>

<template>
    <div class="border-muted grid w-full rounded border lg:grid-cols-2">
        <!-- Attached Roles -->
        <div class="border-muted col-span-1 flex flex-col border-e">
            <div class="bg-body text-body rounded-ss p-4 text-center font-medium">Attached Roles</div>

            <div class="flex flex-col gap-3 px-3 py-4 md:px-4">
                <template v-if="attachedRoleList?.roles?.length > 0">
                    <div
                        v-for="role in attachedRoleList.roles"
                        :key="role.id"
                        class="relative rounded-md border border-green-300 bg-green-300/90 px-3 py-2 text-center text-sm font-medium text-green-800 transition duration-200 hover:bg-green-300 dark:border-green-700/20 dark:bg-green-900/10 dark:text-green-100 hover:dark:bg-green-900/20"
                    >
                        {{ role.name }}
                        <Button
                            variant="destructive"
                            size="icon"
                            class="absolute end-0 top-0 rounded-s-none hover:scale-101"
                            @click="submit('delete', role.id, attachedRoleList.id)"
                        >
                            <Trash2 class="size-4.5" />
                        </Button>
                    </div>
                </template>
                <div v-else class="flex flex-col items-center justify-center gap-2 p-2 text-center">
                    <Inbox class="size-8" />
                    <span class="text-body-muted text-xs font-medium">No roles attached</span>
                </div>
            </div>
        </div>

        <!-- Available Roles -->
        <div class="col-span-1 flex flex-col">
            <div class="bg-body text-body rounded-se p-4 text-center font-medium">Available Roles</div>

            <div class="flex flex-col gap-3 px-3 py-4 md:px-4">
                <template v-if="availableRoleList?.length > 0">
                    <div
                        v-for="role in availableRoleList"
                        :key="role.id"
                        class="relative rounded-md border border-red-300 bg-red-300/90 px-3 py-2 text-center text-sm font-medium text-red-800 transition duration-200 hover:bg-red-300 dark:border-red-700/20 dark:bg-red-900/20 dark:text-red-100 hover:dark:bg-red-900/30"
                    >
                        {{ role.name }}
                        <Button
                            size="icon"
                            class="absolute start-0 top-0 rounded-e-none hover:scale-101"
                            @click="submit('add', role.id, attachedRoleList.id)"
                        >
                            <PlusCircle class="size-4.5" />
                        </Button>
                    </div>
                </template>
                <div v-else class="flex flex-col items-center justify-center gap-2 p-2 text-center">
                    <Inbox class="size-8" />
                    <span class="text-body-muted text-xs font-medium">No roles available</span>
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-1 pt-2 text-base">
        <span class="text-body-muted font-medium">Permission:</span>
        <span class="text-active-link font-bold">{{ attachedRoleList?.name }}</span>
    </div>
</template>
