<script setup lang="ts">
import {
    SidebarGroup,
    SidebarGroupContent,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuBadge,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { Plus, Minus } from 'lucide-vue-next';
import { footerSidebarItems as items } from '@/config/navigation';
import { type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { nextTick } from 'vue';

interface Props {
    // items: NavItem[];
    class?: string;
}

const page = usePage<SharedData>();

defineProps<Props>();

// Track which dropdowns are open
const openDropdowns = ref<string[]>([]);

function toggleDropdown(title: string) {
    if (openDropdowns.value.includes(title)) {
        openDropdowns.value = openDropdowns.value.filter((t) => t !== title);
    } else {
        openDropdowns.value.push(title);
    }
}

function isDropdownOpen(title: string) {
    return openDropdowns.value.includes(title);
}

function onEnter(el: Element) {
    el.style.height = '0';
    el.style.opacity = '0';
    nextTick(() => {
        el.style.transition = 'all 0.3s ease';
        el.style.height = el.scrollHeight + 'px';
        el.style.opacity = '1';
    });
}

function onAfterEnter(el: Element) {
    el.style.height = '';
    el.style.opacity = '';
}

function onLeave(el: HTMLElement) {
    el.style.height = el.scrollHeight + 'px';

    // Force reflow to apply the height before collapsing
    void el.offsetHeight;

    el.style.transition = 'all 0.2s ease';
    el.style.height = '0';
    el.style.opacity = '0';
}

function onAfterLeave(el: Element) {
    el.style.height = '';
    el.style.opacity = '';
}
</script>

<template>
    <SidebarGroup v-for="section in items" :key="section.name" :class="` ${$props.class || ''}`">
        <SidebarGroupContent>
            <SidebarGroupLabel>{{ section.name }}</SidebarGroupLabel>

            <SidebarMenu class="cursor-pointer">
                <SidebarMenuItem v-for="item in section.items" :key="item.title">
                    <SidebarMenuButton
                        as-child
                        :is-active="item.href === page.url"
                        :tooltip="item.title"
                        @click="item.children && toggleDropdown(item.title)"
                    >
                        <template v-if="!item.children">
                            <Link :href="item.href">
                                <component :is="item.icon" v-if="item.icon" />
                                <span>{{ item.title }}</span>
                            </Link>
                        </template>

                        <template v-else>
                            <div>
                                <component :is="item.icon" v-if="item.icon" />
                                <span>{{ item.title }}</span>
                            </div>
                        </template>
                    </SidebarMenuButton>

                    <SidebarMenuBadge v-if="item.children?.length">
                        <Plus v-show="!isDropdownOpen(item.title)" class="h-3 w-3" />
                        <Minus v-show="isDropdownOpen(item.title)" class="h-3 w-3" />
                    </SidebarMenuBadge>

                    <transition name="fade-slide" @enter="onEnter" @after-enter="onAfterEnter" @leave="onLeave" @after-leave="onAfterLeave">
                        <SidebarMenuSub
                            class="overflow-hidden transition-all duration-300"
                            v-show="item.children?.length && isDropdownOpen(item.title)"
                            ref="dropdownRef"
                        >
                            <SidebarMenuSubItem v-for="child in item.children" :key="child.title">
                                <SidebarMenuSubButton :tooltip="child.title" :is-active="child.href === page.url">
                                    <Link :href="child.href" class="w-full">{{ child.title }}</Link>
                                </SidebarMenuSubButton>
                            </SidebarMenuSubItem>
                        </SidebarMenuSub>
                    </transition>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarGroupContent>
    </SidebarGroup>
</template>
