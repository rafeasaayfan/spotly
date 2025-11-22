<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { useVModel } from '@vueuse/core'
import { Button } from '../button';
import { Plus, X } from 'lucide-vue-next';
import { Input } from '.';

const props = defineProps<{
  defaultValue?: (string | number)[]
  modelValue: (string | number)[]
  class?: HTMLAttributes['class']
  placeholder?: string
}>()

const emits = defineEmits<{
  (e: 'update:modelValue', payload: (string | number)[]): void
}>()

// make sure we start with an array
const modelValue = useVModel(props, 'modelValue', emits, {
  passive: true,
  defaultValue: props.defaultValue ?? [''],
})

// add new empty field
function addField() {
  modelValue.value.push('')
}

// remove a field
function removeField(index: number) {
  modelValue.value.splice(index, 1)
}
</script>

<template>
  <div class="space-y-2">
    <div
      v-for="(val, index) in modelValue"
      :key="index"
      class="flex items-center gap-2"
    >
      <Input
        v-model="modelValue[index]"
        :placeholder="props.placeholder"
        v-if="modelValue.length >= 1"
      />

      <div>
        <Button
          type="button"
          size="icon"
          variant="destructive"
          class="rounded-full size-7 bg-[var(--destructive)]/50 dark:bg-[var(--destructive)]/30 
          hover:bg-[var(--destructive)] dark:hover:bg-[var(--destructive)] hover:scale-100 hover:translate-0"
          @click="removeField(index)"
        >
          <X class="size-3" />
        </Button>
      </div>
    </div>

    <Button
      type="button"
      size="sm"
      variant="ghost"
      @click="addField"
    >
      <Plus class="size-3.5" />
      <span>{{ $t('field.multiInput.add') }}</span>
    </button>
  </div>
</template>
