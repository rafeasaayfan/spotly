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
    SidebarSection,
} from '@/components/ui/sidebar';

import { useSidebarNavigation } from '@/composables/navigation/useNavigation';
import useAuth from '@/composables/useAuth';
import { type SidebarSection as SidebarSectionType } from '@/types';

import { Link } from '@inertiajs/vue3';
import { Minus, Plus } from 'lucide-vue-next';

const props = defineProps<{
    mainSidebarItems: SidebarSectionType[],
}>();

const { isActiveUrl, isHasChildActive, toggleDropdown, isDropdownOpen, onEnter, onAfterEnter, onLeave, onAfterLeave } = useSidebarNavigation(props.mainSidebarItems);

const { can } = useAuth();
</script>

<template>
    <SidebarSection v-for="section in mainSidebarItems" :key="section.name">
        <SidebarGroup class="px-2" v-if="section.permission ? can(section.permission) : true">
            <SidebarGroupContent>
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
                                <template v-if="!item.children?.length">
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

                            <SidebarMenuBadge v-if="item.children?.length">
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
                                <SidebarMenuSub
                                    class="overflow-hidden transition-all duration-300"
                                    v-show="item.children?.length && isDropdownOpen(item.title)"
                                    ref="dropdownRef"
                                >
                                    <SidebarMenuSubItem v-for="child in item.children" :key="child.title">
                                        <SidebarMenuSubButton :tooltip="child.title" :is-active="isActiveUrl(child.href ?? '')">
                                            <Link :href="child.href ?? ''" class="w-full h-full flex items-center gap-1.5 px-2">
                                                <component :is="child.icon" v-if="child.icon" class="size-3.5" />
                                                {{ child.title }}
                                            </Link>
                                        </SidebarMenuSubButton>
                                    </SidebarMenuSubItem>
                                </SidebarMenuSub>
                            </transition>
                        </template>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarGroupContent>
        </SidebarGroup>
    </SidebarSection>
</template>
