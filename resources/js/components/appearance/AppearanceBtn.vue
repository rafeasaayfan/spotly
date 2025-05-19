<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance'
import { Moon, Sun } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'

const { appearance, updateAppearance } = useAppearance()

function toggleTheme() {
  updateAppearance(appearance.value === 'light' ? 'dark' : 'light')
}
</script>

<template>
  <Button
    variant="ghost"
    size="icon"
    class="relative size-10 w-auto cursor-pointer rounded-full flex items-center justify-center h-8 w-8 focus-visible:ring-2 focus-visible:ring-primary"
    @click="toggleTheme"
    :title="appearance === 'light' ? 'Switch to dark mode' : 'Switch to light mode'"
  >
    <Sun v-if="appearance === 'light'" class="size-5 text-yellow-500" />
    <Moon v-else class="size-5 text-gray-300" />
  </Button>
</template>
