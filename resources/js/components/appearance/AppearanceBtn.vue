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
    type="button"
    size="icon"
    class="relative cursor-pointer rounded-full"
    @click="toggleTheme"
    :title="appearance === 'light' ? 'Switch to dark mode' : 'Switch to light mode'"
  >
    <Sun v-if="appearance === 'light'" class="size-5 text-yellow-500" />
    <Moon v-else class="size-5" />
  </Button>
</template>
