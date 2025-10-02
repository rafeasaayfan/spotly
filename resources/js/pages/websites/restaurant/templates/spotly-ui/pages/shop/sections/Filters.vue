<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Checkbox, Input, SelectWithSearch } from '@/components/ui/fields';
import { Label } from '@/components/ui/label';
import { FilterIcon } from 'lucide-vue-next';
</script>

<template>
    <aside class="animate-slide-in-left w-full flex-shrink-0 col-span-1 md:pe-8">
        <div
            class="border-muted flex flex-col items-center gap-5 rounded-xl border px-4 py-6 shadow backdrop-blur-xl"
            style="box-shadow: 0 5px 12px 0 rgba(31, 38, 135, 0.18)"
        >
            <div class="border-muted flex w-full items-center gap-2 border-b pb-2">
                <FilterIcon class="text-active-link size-5" />
                <span class="text-active-link text-lg font-bold tracking-wide">Filters</span>
            </div>

            <div class="flex w-full flex-col gap-5">
                <!-- search -->
                <div class="relative w-full">
                    <Input class="w-full pl-9" id="search" type="search" placeholder="searching..." tabindex="1" />
                    <svg
                        class="text-body-muted absolute top-1/2 left-2 h-5 w-5 -translate-y-1/2 transform"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                </div>

                <!-- category -->
                <div class="flex w-full flex-col gap-1">
                    <Label>Category</Label>
                    <SelectWithSearch
                        modelValue=""
                        :options="[{ value: '', label: 'All Categories' }]"
                        placeholder="Category"
                        class="w-full"
                        disabled
                    />
                </div>

                <!-- brand -->
                <div class="flex w-full flex-col gap-1">
                    <Label>Brand</Label>
                    <SelectWithSearch modelValue="" :options="[{ value: '', label: 'All Brands' }]" placeholder="Brand" class="w-full" disabled />
                </div>

                <!-- Price Range -->
                <div class="flex w-full flex-col gap-1">
                    <Label>Price Range</Label>
                    <div class="flex gap-2">
                        <Input type="number" min="0" placeholder="Min" />
                        <Input type="number" min="0" placeholder="Max" />
                    </div>
                </div>

                <!-- Quick Filters -->
                <div class="flex w-full flex-col gap-1">
                    <Label>Quick Filters</Label>
                    <div class="flex flex-col gap-2">
                        <label class="flex cursor-pointer items-center gap-2 text-sm select-none"> <Checkbox /> Free Shipping </label>
                        <label class="flex cursor-pointer items-center gap-2 text-sm select-none"> <Checkbox /> On Sale </label>
                    </div>
                </div>
            </div>

            <Button variant="destructive" size="sm" class="w-full shadow">Reset Filters</Button>
        </div>
    </aside>
</template>
