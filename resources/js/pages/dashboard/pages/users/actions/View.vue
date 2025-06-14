<script setup lang="ts">
import View from '@/components/table/actions/View.vue';

const props = defineProps<{
    data: Record<string, any>;
}>();
</script>

<template>
    <View :data="props.data" table="users" href="" />
</template>
