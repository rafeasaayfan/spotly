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

import { footerSidebarItems } from '@/config/navigations';
import useAuth from '@/composables/useAuth';

import { Minus, Plus } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
import { useSidebarNavigation } from '@/composables/navigation/useNavigation';

const { isActiveUrl, isHasChildActive, toggleDropdown, isDropdownOpen, onEnter, onAfterEnter, onLeave, onAfterLeave } = useSidebarNavigation(footerSidebarItems);

const { can } = useAuth();
</script>

<template>
    <SidebarGroup v-for="section in footerSidebarItems" :key="section.name">
        <SidebarGroupContent v-if="section.permission ? can(section.permission) : true">
            <SidebarGroupLabel>{{ section.name }}</SidebarGroupLabel>

            <SidebarMenu class="cursor-pointer">
                <SidebarMenuItem v-for="item in section.items" :key="item.title">
                    <template v-if="item.permission ? can(item.permission) : true">
                        <SidebarMenuButton
                            as-child
                            :is-active="item.children?.length ? false : isActiveUrl(item.href ?? '')"
                            :is-child-active="item.children?.length ? isHasChildActive(item.children) : false"
                            :tooltip="item.title"
                            @click="item.children && toggleDropdown(item.title)"
                        >
                            <template v-if="!item.children">
                                <Link :href="item.href ?? ''">
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
                                        <Link :href="child.href ?? ''" class="w-full">{{ child.title }}</Link>
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
