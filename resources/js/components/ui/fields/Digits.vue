<script setup lang="ts">
import { nextTick, ref, watch, type HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { useVModel } from '@vueuse/core'
import { Input } from '.';

const props = defineProps<{
    defaultValue?: number
    modelValue?: any
    class?: HTMLAttributes['class']
    length?: number
}>()

const emits = defineEmits<{
    (e: 'update:modelValue', payload: number): void
}>()

const modelValue = useVModel(props, 'modelValue', emits, {
    passive: true,
    defaultValue: props.defaultValue,
})

const digits = ref(Array(props.length ?? 6));

watch(digits, () => {
  modelValue.value = digits.value.join("")
}, { deep: true })

// Handle moving to next input automatically
function onInput(e: Event, index: number) {
  const input = e.target as HTMLInputElement | null;
  const value = input?.value ?? "";

  if (/^\d$/.test(value)) {
    digits.value[index] = value;

    if (index < digits.value.length - 1 && input?.nextElementSibling instanceof HTMLElement) {
      (input.nextElementSibling as HTMLElement).focus();
    }
  } else {
    digits.value[index] = "";
  }
}

// Handle backspace (move to previous input if empty)
function onKeydown(e: KeyboardEvent, index: number) {
  const input = e.target as HTMLInputElement;
  
  if (e.key === 'Backspace') {
    if (digits.value[index] === "" && index > 0) {
      digits.value[index - 1] = "";
      nextTick(() => {
        const prevInput = input.parentElement?.children[index - 1] as HTMLInputElement;
        prevInput?.focus();
      });
    } else {
      // Clear current input
      digits.value[index] = "";
    }
  } else if (e.key === 'ArrowLeft' && index > 0) {
    e.preventDefault();
    const prevInput = input.parentElement?.children[index - 1] as HTMLInputElement;
    prevInput?.focus();
  } else if (e.key === 'ArrowRight' && index < digits.value.length - 1) {
    e.preventDefault();
    const nextInput = input.parentElement?.children[index + 1] as HTMLInputElement;
    nextInput?.focus();
  }
}
</script>

<template>
    <Input 
        v-for="(digit, index) in digits"
        v-model="digits[index]" 
        :key="index" 
        type="text" 
        inputmode="numeric"
        pattern="[0-9]*" maxlength="1"
        :class="cn('w-10 h-12 text-center text-lg', props.class)"
        @input="onInput($event, index)"
        @keydown="onKeydown($event, index)"
    />
</template>
