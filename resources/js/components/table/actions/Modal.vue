<script setup lang="ts">
import axios from 'axios';
import { defineAsyncComponent, DefineComponent, onMounted, ref } from 'vue';

const props = withDefaults(defineProps<{
    action: string;
    path: string;
    routeName: string;
    id?: number;
    dashboardFor?: string;
}>(), {
    dashboardFor: 'default',
});

const record = ref<Record<string, any>>({});
const loaded = ref(false);

const defaultComponents = import.meta.glob('@/pages/dashboard/pages/**/actions/*.vue');
const websiteComponents = import.meta.glob('@/pages/websites/**/dashboard/pages/**/actions/*.vue');
const components = props.dashboardFor === 'default'
  ? defaultComponents
  : websiteComponents;
const matchingPath = Object.keys(components).find((path) =>
    path.includes(`/pages/${props.path}/actions/${props.action.charAt(0).toUpperCase() + props.action.slice(1)}.vue`),
);
if (!matchingPath) {
    throw new Error('Component not found');
}
const actionCompo = defineAsyncComponent(components[matchingPath] as () => Promise<DefineComponent>);

function getRoute(): string | null {
    switch (props.action) {
        case 'create': {
            return route(`${props.routeName}.create`);
        }

        case 'edit': {
            return route(`${props.routeName}.edit`, props.id);
        }

        case 'view': {
            return route(`${props.routeName}.show`, props.id);
        }

        case 'assignRoles': {
            return route(`${props.routeName}.assignRoles`, props.id);
        }

        case 'assignPermissions': {
            return route(`${props.routeName}.assignPermissions`, props.id);
        }

        case 'userAssignments': {
            return route(`${props.routeName}.assignment`, props.id);
        }

        default:
            return null;
    }
}

onMounted(async () => {
    const endpoint = getRoute();
    if (!endpoint) {
        loaded.value = true;
        return;
    }

    try {
        const response = await axios.get(endpoint);
        record.value = response.data.props;
    } catch (error) {
        console.error('Failed to fetch record:', error);
    } finally {
        loaded.value = true;
    }
});
</script>

<template>
    <div class="min-h-20 transition-all duration-100 ease-in-out pt-5">
        <div v-if="!loaded" class="flex h-full items-center justify-center">
            <span class="text-body-muted text-base font-medium">Please wait...</span>
        </div>
        <component v-else :is="actionCompo" v-bind="record" />
    </div>
</template>
