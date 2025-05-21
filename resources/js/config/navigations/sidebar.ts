import { type SidebarSection } from '@/types';
import { HomeIcon, LayoutGrid, PercentDiamondIcon, UsersIcon } from 'lucide-vue-next';

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
            title: 'Restaurants',
            href: '/dashboard/restaurants',
            icon: HomeIcon,
            children: []
        }
    ],
    },
];

export const footerSidebarItems: SidebarSection[] = [
    {
        name: 'Roles & Permissions',
        items: [{
            title: 'Assignments',
            href: '/dashboard/assignmets',
            icon: PercentDiamondIcon,
            children: [
                {
                    title: 'Permissions',
                    href: '/dashboard/assignmets/permissions',
                },
                {
                    title: 'Roles',
                    href: '/dashboard/assignmets/roles',
                },
            ]
        }],
    },
];

//? Sidebar UI
export type SidebarVariant = 'sidebar' | 'floating' | 'inset';

export const sidebarVariant: SidebarVariant = 'sidebar';
