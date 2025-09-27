import { type SidebarSection } from '@/types';
import { icons } from 'lucide-vue-next';

//? Sidebar Content
export const mainSidebarItems: SidebarSection[] = [
    {
        name: 'Main',
        items: [
            {
                title: 'Dashboard',
                href: '/dashboard',
                icon: icons.LayoutDashboard,
            },
            {
                title: 'Users',
                href: '/dashboard/users',
                icon: icons.Users,
            },
            {
                title: 'Messages',
                href: '/dashboard/messages',
                icon: icons.MessageCircle,
            },
        ],
    },
    {
        name: 'E-Commerce',
        items: [
            {
                title: 'Categories',
                href: '/dashboard/categories',
                icon: icons.FolderTree,
            },
            {
                title: 'Brands',
                href: '/dashboard/brands',
                icon: icons.Tags,
            },
            {
                title: 'Products',
                href: '/dashboard/products',
                icon: icons.Package,
            },
        ],
    },
];

export const footerSidebarItems: SidebarSection[] = [
    {
        name: 'Spotly Settings',
        items: [
            {
                title: 'Go To Your Website Settings',
                href: '/dashboard/assignments/permissions',
                icon: icons.Diamond,
            },
        ],
    },
];