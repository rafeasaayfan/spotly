<script setup lang="ts">
import { CheckCircle } from 'lucide-vue-next';

interface Props {
    templateId: string,
    selectedTemplateId: string | number
    templateName: string
    animate: boolean
}

const props = defineProps<Props>();
</script>

<template>
    <button type="button"
        class="border-[var(--border)] text-body relative flex min-h-26 min-w-40 cursor-pointer items-center justify-center 
        rounded-lg border bg-black/3 transition-all duration-200 ease-in-out hover:-translate-y-1 hover:scale-102 
        hover:bg-black/4 active:scale-98 dark:bg-white/3 dark:hover:bg-white/4 backdrop-blur"
        :class="props.selectedTemplateId === props.templateId
                ? 'text-active -translate-y-1 scale-102 bg-black/4 font-bold dark:bg-white/4 border-[var(--primary)]/30'
                : ''
            ">
        <div v-if="props.selectedTemplateId === props.templateId"
            class="absolute top-0 left-0 m-5 h-1/2 w-1/2 bg-[var(--success)]/20 dark:bg-[var(--success)]/10 blur-xl"></div>

        <h2 class="text-lg font-bold">{{ props.templateName }}</h2>

        <div v-if="props.selectedTemplateId === props.templateId"
            class="translate-all absolute end-1 top-1 z-10 flex size-6 items-center justify-center rounded-md 
            bg-gradient-to-br from-[var(--primary)] via-[var(--primary-hover)] to-[var(--primary-active)] transition-all duration-300 ease-in-out"
            :class="props.animate ? 'translate-x-0 scale-100 rotate-0 opacity-100' : 'translate-x-1 scale-75 rotate-90 opacity-0'
                ">
            <CheckCircle class="size-4 text-white" />
        </div>
    </button>
</template>