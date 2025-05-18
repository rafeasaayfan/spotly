import { type SidebarSection } from '@/types';
import { LayoutGrid, PercentDiamondIcon, UsersIcon } from 'lucide-vue-next';

//? Sidebar Content
export const mainSidebarItems: SidebarSection[] = [
    {
        name: 'Platform',
        items: [{
            title: 'Dashboard',
            href: '/dashboard',
            icon: LayoutGrid,
        }]
    },
    {
        name: 'Web',
        items: [{
            title: 'Users',
            href: '/dashboard/users',
            icon: UsersIcon,
            children: []
        },
        {
            title: 'Projects',
            href: '/dashboard/projects',
            icon: UsersIcon,
            children: [
                {
                    title: 'All Users',
                    href: '/users/all',
                },
                {
                    title: 'Create User',
                    href: '/users/create',
                },
            ]
        }
    ],
    },
];

export const footerSidebarItems: SidebarSection[] = [
    {
        name: 'Roles & Permissions',
        items: [{
            title: 'Assigments',
            href: '/dashboard/assigments',
            icon: PercentDiamondIcon,
            children: [
                {
                    title: 'All Users',
                    href: '/users/all',
                },
                {
                    title: 'Create User',
                    href: '/users/create',
                },
            ]
        }],
    },
];

//? Sidebar UI
export type SidebarVariant = 'sidebar' | 'floating' | 'inset';

export const sidebarVariant: SidebarVariant = 'sidebar';
