<script setup lang="ts">
import { ref, computed, type HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';
import { usePage } from '@inertiajs/vue3';
import { SharedData } from '@/types';

const page = usePage<SharedData>();

const props = defineProps<{
  id?: string
  class?: HTMLAttributes['class']
  label?: string
  modelValue: boolean
}>()

// This means that the component is a controlled component
// and the value is passed from the parent component
// and the component will emit an event to update the value
// and is used to create a two-way binding between the parent and child components
// The parent component will pass the value to the child component
// and the child component will emit an event to update the value
const emits = defineEmits<{
  (e: 'update:modelValue', value: boolean): void
}>()

const toggleChecked = computed({
  get: () => props.modelValue,
  set: (val: boolean) => emits('update:modelValue', val),
})

// const toggleChecked = ref(props.modelValue);

const isRTL = computed(() => page.props.lang === 'ar')
</script>

<template>
  <div
    @click="toggleChecked = !toggleChecked"
    :class="cn('group flex items-center gap-2 cursor-pointer transition-all ease-in-out duration-150', props.class)"
    :id="props.id"
  >
    <button
      :class="[
        'relative flex items-center w-8 h-5 rounded-full border cursor-pointer transition-all ease-in-out duration-150 active:scale-96',
        toggleChecked
          ? 'bg-primary border-muted'
          : 'bg-gray-200 dark:bg-gray-900 hover:bg-gray-300 dark:hover:bg-gray-950 border-muted'
      ]"
    >
      <div
        :class="[
          'absolute w-3 h-3 rounded-full transition-all ease-in-out duration-500',
          toggleChecked
            ? isRTL
              ? 'left-[2.5px] bg-slate-950 dark:bg-slate-100'
              : 'right-[2.5px] bg-slate-950 dark:bg-slate-100'
            : isRTL
              ? 'right-[2.5px] bg-slate-500/50 dark:bg-slate-600/50'
              : 'left-[2.5px] bg-slate-500/50 dark:bg-slate-600/50'
        ]"
      ></div>
    </button>

    <span
      v-if="props.label"
      class="text-sm"
      :class="toggleChecked ? 'text-body' : 'text-body-muted'"
    >
      {{ props.label.charAt(0).toUpperCase() + props.label.slice(1) }}
    </span>
  </div>
</template>
