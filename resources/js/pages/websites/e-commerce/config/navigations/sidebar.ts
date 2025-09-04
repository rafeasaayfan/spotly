import { type SidebarSection } from '@/types';
import { icons } from 'lucide-vue-next';

//? Sidebar Content
export const mainSidebarItems: SidebarSection[] = [
    {
        name: 'Platform',
        items: [
            {
                title: 'Dashboard',
                href: '/dashboard',
                icon: icons.LayoutGrid,
            },
        ],
    },
];

export const footerSidebarItems: SidebarSection[] = [
    {
        name: 'Roles & Permissions',
        permission: ['assignments_access'],
        items: [
            {
                title: 'Assignments',
                permission: ['assignments_access'],
                icon: icons.Diamond,
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