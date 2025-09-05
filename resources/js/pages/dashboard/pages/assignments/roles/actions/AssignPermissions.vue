<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { Inbox, PlusCircle, Trash2 } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
    attachedPermissions: Record<string, any>;
    availablePermissions: Record<string, any>;
}>();

const attachedPermissions = ref<Record<string, any>>(props.attachedPermissions);
const availablePermissions = ref<Record<string, any>>(props.availablePermissions);

watch(
    () => props.attachedPermissions,
    (newVal) => {
        attachedPermissions.value = newVal;
    },
    { immediate: true },
);

watch(
    () => props.availablePermissions,
    (newVal) => {
        availablePermissions.value = newVal;
    },
    { immediate: true },
);

const attachedPermissionList = computed(() => attachedPermissions.value ?? []);
const availablePermissionList = computed(() => availablePermissions.value ?? []);

const disabledSubmit = ref(false);

function submit(action: 'delete' | 'add', permissionId: number, roleId: number) {
    disabledSubmit.value = true;

    router.post(
        route('dashboard.roles.storeAssignments', { id: roleId }),
        {
            action: action,
            id: permissionId,
        },
        {
            async onSuccess() {
                try {
                    const response = await axios.get(route('dashboard.roles.assignPermissions', roleId));

                    attachedPermissions.value = response.data.props.attachedPermissions;
                    availablePermissions.value = response.data.props.availablePermissions;

                    disabledSubmit.value = false;
                } catch (error) {
                    console.error('Failed to fetch record:', error);
                }
            },
        },
    );
}
</script>

<template>
    <div class="px-4 pb-3">
        <div class="border-muted grid w-full rounded border lg:grid-cols-2">
            <div class="border-muted col-span-1 flex flex-col border-e">
                <div class="bg-body text-body rounded-ss p-4 text-center font-medium">Attached Permissions</div>

                <div class="flex flex-col gap-3 px-3 py-4 md:px-4">
                    <template v-if="attachedPermissionList?.permissions?.length > 0">
                        <div
                            v-for="permission in attachedPermissionList.permissions"
                            :key="permission.id"
                            class="relative rounded-md border border-green-300 bg-green-300/90 px-3 py-2 text-center text-sm font-medium text-green-800 transition duration-200 hover:bg-green-300 dark:border-green-700/20 dark:bg-green-900/10 dark:text-green-100 hover:dark:bg-green-900/20"
                        >
                            {{ permission.name }}

                            <Button
                                variant="destructive"
                                size="icon"
                                class="absolute end-0 top-0 rounded-s-none hover:scale-101"
                                @click="submit('delete', permission.id, attachedPermissionList.id)"
                                :disabled="disabledSubmit"
                            >
                                <Trash2 class="size-4.5" />
                            </Button>
                        </div>
                    </template>

                    <div v-else class="flex flex-col items-center justify-center gap-2 p-2 text-center">
                        <Inbox class="size-8" />
                        <span class="text-body-muted text-xs font-medium">No permissions attached</span>
                    </div>
                </div>
            </div>

            <div class="col-span-1 flex flex-col">
                <div class="bg-body text-body rounded-se p-4 text-center font-medium">Available Permissions</div>

                <div class="flex flex-col gap-3 px-3 py-4 md:px-4">
                    <template v-if="availablePermissionList?.length > 0">
                        <div
                            v-for="permission in availablePermissionList"
                            :key="permission.id"
                            class="relative rounded-md border border-red-300 bg-red-300/90 px-3 py-2 text-center text-sm font-medium text-red-800 transition duration-200 hover:bg-red-300 dark:border-red-700/20 dark:bg-red-900/20 dark:text-red-100 hover:dark:bg-red-900/30"
                        >
                            {{ permission.name }}

                            <Button
                                size="icon"
                                class="absolute start-0 top-0 rounded-e-none hover:scale-101"
                                @click="submit('add', permission.id, attachedPermissionList.id)"
                                :disabled="disabledSubmit"
                            >
                                <PlusCircle class="size-4.5" />
                            </Button>
                        </div>
                    </template>
                    <div v-else class="flex flex-col items-center justify-center gap-2 p-2 text-center">
                        <Inbox class="size-8" />
                        <span class="text-body-muted text-xs font-medium">No roles availabe</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-1 pt-2 text-base">
            <span class="text-body-muted font-medium">Role:</span>
            <span class="text-active-link font-bold">{{ attachedPermissionList?.name }}</span>
        </div>
    </div>
</template>
