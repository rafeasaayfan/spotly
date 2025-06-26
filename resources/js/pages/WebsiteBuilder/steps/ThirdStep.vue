<script setup lang="ts">
import HeadingSmall from '@/components/headers/HeadingSmall.vue';
import Heading from '@/components/headers/Heading.vue';
import { Input, InputError } from '@/components/ui/fields';
import { computed } from 'vue';

const props = defineProps<{
    form: {
        phone_number: string;
        email: string;
        errors?: Record<string, string>;
    };
}>();

const emit = defineEmits<{
    (e: 'update', field: string, value: string): void;
}>();


</script>

<template>
    <div key="step3" class="grid grid-cols-1 gap-6 md:grid-cols-2">

    </div>
</template>
