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

import useAuth from '@/composables/useAuth';
import { type SidebarSection as SidebarSectionType } from '@/types';

import { useSidebarNavigation } from '@/composables/navigation/useNavigation';
import { Link } from '@inertiajs/vue3';
import { Minus, Plus } from 'lucide-vue-next';

const props = defineProps<{
    footerSidebarItems: SidebarSectionType[];
}>();

const { isActiveUrl, isHasChildActive, toggleDropdown, isDropdownOpen, onEnter, onAfterEnter, onLeave, onAfterLeave } = useSidebarNavigation(
    props.footerSidebarItems,
);

const { canAny, hasAnyRole, websiteHasAnyRole } = useAuth();
</script>

<template>
    <SidebarGroup v-for="section in footerSidebarItems" :key="section.name">
        <SidebarGroupContent
            v-if="
                (section.permission ? canAny(section.permission) : true) &&
                (section.role ? hasAnyRole(section.role) : true) &&
                (section.websiteRole ? websiteHasAnyRole(section.websiteRole) : true)
            "
        >
            <SidebarGroupLabel>{{ section.name }}</SidebarGroupLabel>

            <SidebarMenu class="cursor-pointer">
                <SidebarMenuItem v-for="item in section.items" :key="item.title">
                    <template
                        v-if="
                            (item.permission ? canAny(item.permission) : true) &&
                            (item.role ? hasAnyRole(item.role) : true) &&
                            (item.websiteRole ? websiteHasAnyRole(item.websiteRole) : true)
                        "
                    >
                        <SidebarMenuButton
                            as-child
                            :is-active="item.children?.length ? false : isActiveUrl(item.href ?? '')"
                            :is-child-active="item.children?.length ? isHasChildActive(item.children) : false"
                            :tooltip="item.title"
                            @click="item.children && toggleDropdown(item.title)"
                        >
                            <template v-if="!item.children">
                                <Link v-if="!item.hrefType || item.hrefType === 'link'" :href="item.href ?? ''">
                                    <component :is="item.icon" v-if="item.icon" />
                                    <span>{{ item.title }}</span>
                                </Link>

                                <a v-if="item.hrefType === 'a'" :href="item.href ?? ''" target="_blank" rel="noopener noreferrer">
                                    <component :is="item.icon" v-if="item.icon" />
                                    <span>{{ item.title }}</span>
                                </a>
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

                        <transition
                            v-if="item.children?.length"
                            name="fade-slide"
                            @enter="onEnter"
                            @after-enter="onAfterEnter"
                            @leave="onLeave"
                            @after-leave="onAfterLeave"
                        >
                            <SidebarMenuSub class="overflow-hidden transition-all duration-300" v-show="isDropdownOpen(item.title)" ref="dropdownRef">
                                <SidebarMenuSubItem v-for="child in item.children" :key="child.title">
                                    <SidebarMenuSubButton :tooltip="child.title" :is-active="isActiveUrl(child.href ?? '')">
                                        <Link
                                            v-if="!item.hrefType || item.hrefType === 'link'"
                                            :href="child.href ?? ''"
                                            class="flex h-full w-full items-center gap-1.5 px-2"
                                        >
                                            <component :is="child.icon" v-if="child.icon" class="size-3.5" />
                                            {{ child.title }}
                                        </Link>

                                        <a
                                            v-if="item.hrefType === 'a'"
                                            :href="item.href ?? ''"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="flex h-full w-full items-center gap-1.5 px-2"
                                        >
                                            <component :is="item.icon" v-if="item.icon" />
                                            <span>{{ item.title }}</span>
                                        </a>
                                    </SidebarMenuSubButton>
                                </SidebarMenuSubItem>
                            </SidebarMenuSub>
                        </transition>
                    </template>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarGroupContent>
    </SidebarGroup>
</template>
