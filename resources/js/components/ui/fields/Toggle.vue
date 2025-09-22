<script setup lang="ts">
import { computed, type HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';
import { usePage } from '@inertiajs/vue3';
import { SharedData } from '@/types';

const page = usePage<SharedData>();

const props = defineProps<{
  id?: string
  class?: HTMLAttributes['class']
  btnClass?: HTMLAttributes['class']
  circleClass?: HTMLAttributes['class']
  label?: string
  modelValue: boolean
}>()

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
    :class="cn('group flex items-center gap-2 w-fit transition-all ease-in-out duration-150 ease-in-out', props.class)"
    :id="props.id"
  >
    <button
      type="button"
      :class="[
        cn('relative flex items-center w-8 h-5 rounded-full border cursor-pointer transition-all ease-in-out duration-150 active:scale-96', props.btnClass),
        toggleChecked
          ? 'bg-primary border-muted'
          : 'bg-gray-200 dark:bg-gray-900 hover:bg-gray-300 dark:hover:bg-gray-950 border-muted'
      ]"
    >
      <div
        :class="[
          cn('absolute size-3 rounded-full transition-all ease-in-out duration-500', props.circleClass),
          toggleChecked
            ? isRTL
              ? 'left-[2.5px] bg-white'
              : 'right-[2.5px] bg-white'
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
