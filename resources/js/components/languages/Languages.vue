<script setup lang="ts">
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuTrigger,
  DropdownMenuItem,
  DropdownMenuLabel,
} from '@/components/ui/dropdown-menu'
import { Button } from '@/components/ui/button'
import { LucideGlobe } from 'lucide-vue-next'
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger as-child>
      <Button
        variant="ghost"
        size="icon"
        class="relative w-auto cursor-pointer rounded-full h-8 w-8 flex items-center justify-center focus-within:ring-2 focus-within:ring-primary"
      >
        <LucideGlobe class="size-5 hidden lg:block" />
        <span class="block lg:hidden px-3 py-2 bg-gray-500/20 rounded-md">Languages</span>
      </Button>
    </DropdownMenuTrigger>

    <DropdownMenuContent align="end" class="w-56">
      <!-- Language Options -->
      <DropdownMenuLabel class="text-xs uppercase tracking-wide text-muted-foreground px-2 py-1 mb-2">Languages</DropdownMenuLabel>
      <DropdownMenuItem>
        <Link :href="route('setLang', { lang: 'en' })">English</Link>
    </DropdownMenuItem>
      <DropdownMenuItem>
        <Link :href="route('setLang', { lang: 'fr' })">Français</Link>

      </DropdownMenuItem>
      <DropdownMenuItem>
<Link :href="route('setLang', { lang: 'ar' })">العربية</Link>

      </DropdownMenuItem>


    </DropdownMenuContent>
  </DropdownMenu>
</template>
