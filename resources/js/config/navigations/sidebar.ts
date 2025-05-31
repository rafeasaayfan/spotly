import { type SidebarSection } from '@/types';
import { LayoutGrid, PercentDiamondIcon, UsersIcon } from 'lucide-vue-next';

//? Sidebar Content
export const mainSidebarItems: SidebarSection[] = [
    {
        name: 'Platform',
        items: [
            {
                title: 'Dashboard',
                href: '/dashboard',
                icon: LayoutGrid,
            },
        ],
    },
    {
        name: 'Web',
        items: [
            {
                title: 'Users',
                href: '/dashboard/users',
                icon: UsersIcon,
            },
        ],
    },
];

export const footerSidebarItems: SidebarSection[] = [
    {
        name: 'Roles & Permissions',
        permission: 'assignments_access',
        items: [
            {
                title: 'Assignments',
                href: '/dashboard/assignments',
                permission: 'assignments_access',
                icon: PercentDiamondIcon,
                children: [
                    {
                        title: 'Permissions',
                        href: '/dashboard/assignments/permissions',
                    },
                    {
                        title: 'Roles',
                        href: '/dashboard/assignments/roles',
                    },
                    {
                        title: 'Users assignments',
                        href: '/dashboard/assignments/usersAssignments',
                    },
                ],
            },
        ],
    },
];

//? Sidebar UI
export type SidebarVariant = 'sidebar' | 'floating' | 'inset';
export type sidebarCollapsible = 'offcanvas' | 'icon' | 'none';

export const sidebarVariant: SidebarVariant = 'sidebar';
export const sidebarCollapsible: sidebarCollapsible = 'icon';
