<script setup lang="ts">
import { CheckCircle } from 'lucide-vue-next';

interface Props {
    templateId: string | number,
    selectedTemplateId: string | number
    templateName: string
    animate: boolean
}

const props = defineProps<Props>();
</script>

<template>
    <button type="button"
        class="border-[var(--border)] text-body relative flex min-h-26 min-w-40 cursor-pointer items-center justify-center 
        rounded-lg border transition-all duration-200 ease-in-out hover:-translate-y-1 hover:scale-102 
        hover:bg-black/4 active:scale-98 dark:hover:bg-white/4 backdrop-blur bg-black/3 dark:bg-white/3"
        :class="props.selectedTemplateId === props.templateId
                ? 'text-active -translate-y-1 scale-102 font-bold bg-black/6 dark:bg-white/6'
                : ''
            ">

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