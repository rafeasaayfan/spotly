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
import { footerSidebarItems as items } from '@/config/navigations';
import { type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { Minus, Plus } from 'lucide-vue-next';
import { nextTick, onMounted, ref } from 'vue';

interface Props {
    // items: NavItem[];
    class?: string;
}

const page = usePage<SharedData>();

defineProps<Props>();

// Track which dropdowns are open
const openDropdowns = ref<string[]>([]);

function isActiveUrl(href: string): boolean {
    // if(href === '/dashboard') {
    //     return href === page.url;
    // }
    return href === page.url || page.url.startsWith(href);
}

function isHasChildActive(children: Array<{ href: string }>): boolean {
    return children.some((child) => {
        return child.href === page.url || page.url.startsWith(child.href);
    });
}

function toggleDropdown(title: string) {
    if (openDropdowns.value.includes(title)) {
        openDropdowns.value = openDropdowns.value.filter((t) => t !== title);
    } else {
        openDropdowns.value.push(title);
    }
}

function isDropdownOpen(title: string) : boolean {
    return openDropdowns.value.includes(title);
}

// Initialize openDropdowns on component mount
onMounted(() => {
    items.forEach(section => {
        section.items.forEach(item => {
            if (item.children && isHasChildActive(item.children)) {
                if (!openDropdowns.value.includes(item.title)) {
                    openDropdowns.value.push(item.title);
                }
            }
        });
    });
});

function onEnter(el: Element) {
    const element = el as HTMLElement;

    element.style.height = '0';
    element.style.opacity = '0';
    nextTick(() => {
        element.style.transition = 'all 0.3s ease';
        element.style.height = el.scrollHeight + 'px';
        element.style.opacity = '1';
    });
}

function onAfterEnter(el: Element) {
    const element = el as HTMLElement;

    element.style.height = '';
    element.style.opacity = '';
}

function onLeave(el: Element) {
    const element = el as HTMLElement;

    element.style.height = el.scrollHeight + 'px';

    // Force reflow to apply the height before collapsing
    void element.offsetHeight;

    element.style.transition = 'all 0.2s ease';
    element.style.height = '0';
    element.style.opacity = '0';
}

function onAfterLeave(el: Element) {
    const element = el as HTMLElement;

    element.style.height = '';
    element.style.opacity = '';
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
                        :is-active="item.children?.length ? false : isActiveUrl(item.href)"
                        :is-child-active="item.children?.length ? isHasChildActive(item.children) : false"
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

                    <SidebarMenuBadge v-if="item.children?.length" :is-active="item.children ? isHasChildActive(item.children) : false">
                        <Plus v-show="!isDropdownOpen(item.title)" class="h-3 w-3" />
                        <Minus v-show="isDropdownOpen(item.title)" class="h-3 w-3" />
                    </SidebarMenuBadge>

                    <transition v-if="item.children?.length" name="fade-slide" @enter="onEnter" @after-enter="onAfterEnter" @leave="onLeave" @after-leave="onAfterLeave">
                        <SidebarMenuSub
                            class="overflow-hidden transition-all duration-300"
                            v-show="isDropdownOpen(item.title)"
                            ref="dropdownRef"
                        >
                            <SidebarMenuSubItem v-for="child in item.children" :key="child.title">
                                <SidebarMenuSubButton :tooltip="child.title" :is-active="isActiveUrl(child.href)">
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
