<script setup lang="ts">
import { formatters } from '@/lib/dataTable';

const props = defineProps<{
    data: Record<string, any>;
}>();
</script>

<template>
    <div class="relative pb-3">
        <div class="border-muted relative z-10 grid grid-cols-1 gap-5 border-b px-4">
            <!-- User Details -->
            <div class="border-muted grid gap-4">
                <!-- General Information -->
                <div class="border-muted flex flex-col gap-2 border-b pb-4">
                    <h3 class="text-active-link text-2xl font-bold">{{ props.data.name }}</h3>
                    <div class="flex flex-wrap items-center gap-2">
                        <div v-html="formatters.emailVerified(props.data.email_verified_at)"></div>
                        <div v-html="formatters.status(props.data.status)"></div>
                    </div>
                </div>

                <div class="border-muted border-muted flex flex-col gap-3 border-b pb-4">
                    <p class="text-body-muted flex w-full items-center justify-between text-sm">
                        Email:
                        <a :href="`mailto:${props.data.email}`" class="text-body text-base font-medium">
                            {{ props.data.email }}
                        </a>
                    </p>
                    <p v-if="props.data.phone_number" class="text-body-muted flex w-full items-center justify-between text-sm">
                        Phone Number:
                        <a :href="`tel:${props.data.phone_number}`" class="text-body text-base font-medium">
                            {{ props.data.phone_number }}
                        </a>
                    </p>
                </div>

                <div
                    v-if="props.data.roles.length > 0" class="border-muted flex flex-col gap-1.5 pb-4"
                    :class="props.data.permissions.length > 0 || props.data.websites.length > 0 ? 'border-b' : ''"
                >
                    <span class="text-body-muted text-sm">Roles:</span>
                    <div class="flex flex-wrap items-center gap-3">
                        <div
                            v-for="role in props.data.roles"
                            :key="role.id"
                            class="bg-content rounded px-2 py-1.5 text-sm shadow"
                        >
                            {{ role.name }}
                        </div>
                    </div>
                </div>

                <div 
                    v-if="props.data.permissions.length > 0" class="border-muted border-muted flex flex-col gap-1.5 pb-4"
                    :class="props.data.websites.length > 0 ? 'border-b' : ''"
                >
                    <span class="text-body-muted text-sm">Permissions:</span>
                    <div class="flex flex-wrap items-center gap-3">
                        <div
                            v-for="permission in props.data.permissions"
                            :key="permission.id"
                            class="bg-content rounded px-2 py-1.5 text-sm shadow"
                        >
                            {{ permission.name }}
                        </div>
                    </div>
                </div>

                <div v-if="props.data.websites.length > 0" class="flex flex-col gap-1.5 pb-4">
                    <span class="text-body-muted text-sm">Websites:</span>
                    <div class="flex flex-wrap items-center gap-3">
                        <a
                            v-for="website in props.data.websites"
                            :key="website.id"
                            :href="`/dashboard/websites?search=${website.name}`"
                            :title="website.is_active ? 'Active Website' : 'Inactive Website'"
                            target="_blank"
                            rel="noopener"
                            class="rounded px-2 py-1.5 text-sm cursor-pointer"
                            :class="website.is_active ? 'bg-success text-for-bg-success' : 'bg-destructive text-for-bg-destructive'"
                        >
                            {{ website.name }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-body-muted flex items-center justify-between gap-1 px-4 pt-4 text-xs">
            Created At: <span class="font-medium">{{ formatters.date(props.data.created_at, 'long') }}</span>
        </p>
        <p class="text-body-muted flex items-center justify-between gap-1 px-4 pt-4 text-xs">
            Updated At: <span class="font-medium">{{ formatters.date(props.data.updated_at, 'long') }}</span>
        </p>
    </div>
</template>
