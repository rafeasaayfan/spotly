<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { Inbox, PlusCircle, Trash2 } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
    attached: Record<string, any>;
    availableRoles: Record<string, any>;
    availablePermissions: Record<string, any>;
}>();

const type = ref<'roles' | 'permissions'>('roles');

const attached = ref<Record<string, any>>(props.attached);
const availableRoles = ref<Record<string, any>>(props.availableRoles);
const availablePermissions = ref<Record<string, any>>(props.availablePermissions);

watch(
    () => props.attached,
    (newVal) => {
        attached.value = newVal;
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
watch(
    () => props.availablePermissions,
    (newVal) => {
        availablePermissions.value = newVal;
    },
    { immediate: true },
);

const attachedList = computed(() => attached.value ?? []);
const availableRoleList = computed(() => availableRoles.value ?? []);
const availablePermissionList = computed(() => availablePermissions.value ?? []);

const availableList = computed(() => {
    return type.value === 'roles' ? availableRoleList.value : availablePermissionList.value;
});

const disabledSubmit = ref(false);

function submit(action: 'delete' | 'add', assignmentId: number, type: string, userId: number) {
    disabledSubmit.value = true;

    router.post(
        route('dashboard.userAssignments.storeAssignments', { id: userId }),
        {
            action: action,
            type: type,
            id: assignmentId,
        },
        {
            async onSuccess() {
                try {
                    const response = await axios.get(route('dashboard.userAssignments.assignment', userId));

                    attached.value = response.data.props.attached;
                    availableRoles.value = response.data.props.availableRoles;
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
    <div class="flex flex-col gap-2 pb-3 px-4">
        <div class="flex w-fit gap-2 rounded-md">
            <Button :variant="type === 'roles' ? 'default' : 'outline'" @click="type = 'roles'">Roles</Button>

            <Button :variant="type === 'permissions' ? 'default' : 'outline'" @click="type = 'permissions'">Permissions</Button>
        </div>

        <div class="border-muted grid w-full rounded border lg:grid-cols-2">
            <div class="border-muted col-span-1 flex flex-col border-e">
                <div class="bg-body text-body rounded-ss p-4 text-center font-medium">
                    Attached {{ type.charAt(0).toUpperCase() + type.slice(1) }}
                </div>

                <div class="flex flex-col gap-3 px-3 py-4 md:px-4">
                    <template v-if="attachedList?.[type]?.length > 0">
                        <div
                            v-for="item in attachedList[type]"
                            :key="item.id"
                            class="relative rounded-md border border-green-300 bg-green-300/90 px-3 py-2 text-center text-sm font-medium text-green-800 transition duration-200 hover:bg-green-300 dark:border-green-700/20 dark:bg-green-900/10 dark:text-green-100 hover:dark:bg-green-900/20"
                        >
                            {{ item.name }}
                            <Button
                                variant="destructive"
                                size="icon"
                                class="absolute end-0 top-0 rounded-s-none hover:scale-101"
                                @click="submit('delete', item.id, type, attachedList.id)"
                                :disabled="disabledSubmit"
                            >
                                <Trash2 class="size-4.5" />
                            </Button>
                        </div>
                    </template>

                    <div v-else class="flex flex-col items-center justify-center gap-2 p-2 text-center">
                        <Inbox class="size-8" />
                        <span class="text-body-muted text-xs font-medium">No {{ type }} attached</span>
                    </div>
                </div>
            </div>

            <div class="col-span-1 flex flex-col">
                <div class="bg-body rouneded-se-md text-body p-4 text-center font-medium">
                    Available {{ type.charAt(0).toUpperCase() + type.slice(1) }}
                </div>

                <div class="flex flex-col gap-3 px-3 py-4 md:px-4">
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
                                @click="submit('add', item.id, type, attachedList.id)"
                                :disabled="disabledSubmit"
                            >
                                <PlusCircle class="size-4.5" />
                            </Button>
                        </div>
                    </template>
                    <div v-else class="flex flex-col items-center justify-center gap-2 p-2 text-center">
                        <Inbox class="size-8" />
                        <span class="text-body-muted text-xs font-medium">No {{ type }} available</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-1 text-base">
            <span class="text-body-muted font-medium">User:</span>
            <span class="text-active-link font-bold">{{ attached?.name }}</span>
        </div>
    </div>
</template>
