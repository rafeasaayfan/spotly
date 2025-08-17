import { type SidebarSection } from '@/types';
import { icons } from 'lucide-vue-next';

//? Sidebar Content
export const mainSidebarItems: SidebarSection[] = [
    {
        name: 'Platform',
        items: [
            {
               permission: ['dashboard_access'],
                title: 'Dashboard',
                href: '/dashboard',
                icon: icons.LayoutGrid,
            },
            {
                 title: 'Client',
                 href: '/client/my-websites',
                 icon: icons.LayoutGrid,
             },
        ],
    },
    {
        name: 'Spotly',
        permission: ['dashboard_access'],
        items: [
            {
                title: 'Spotly',
                icon: icons.Settings,
                children: [
                    {
                        title: 'Users',
                        href: '/dashboard/users',
                        icon: icons.Users,
                    },
                    {
                        title: 'Website types',
                        href: '/dashboard/website-types',
                        icon: icons.Type,
                    },
                    {
                        title: 'Countries',
                        href: '/dashboard/countries',
                        icon: icons.Flag,
                    },
                    {
                        title: 'Payment Methods',
                        href: '/dashboard/payment-methods',
                        icon: icons.CreditCard,
                    },
                    {
                        title: 'Messages',
                        href: '/dashboard/messages',
                        icon: icons.MessageCircle,
                    },
                    {
                        title: 'Email subscribers',
                        href: '/dashboard/email-subscribers',
                        icon: icons.Mail,
                    },
                    {
                        title: 'Plans',
                        href: '/dashboard/plans',
                        icon: icons.CreditCard,
                    },
                ],
            },
            {
                title: 'UI',
                icon: icons.LayoutTemplate,
                children: [
                    {
                        title: 'Templates',
                        href: '/dashboard/ui/templates',
                    },
                    {
                        title: 'Template Colors',
                        href: '/dashboard/ui/template-colors',
                    },
                    {
                        title: 'Template Template Colors',
                        href: '/dashboard/ui/template-template-colors',
                    },
                ],
            },
        ],
    },
    {
        name: 'Websites',
        permission: ['dashboard_access'],
        items: [
            {
                title: 'Management',
                icon: icons.Settings,
                children: [
                    {
                        title: 'Websites',
                        href: '/dashboard/websites',
                        icon: icons.Monitor,
                    },
                    {
                        title: 'Website Users',
                        href: '/dashboard/website-users',
                        icon: icons.Users,
                    },
                    {
                        title: 'Categories',
                        href: '/dashboard/categories',
                        icon: icons.Tag,
                    },
                    {
                        title: 'Brands',
                        href: '/dashboard/brands',
                        icon: icons.Building2,
                    },
                    {
                        title: 'Website Templates',
                        href: '/dashboard/website-templates',
                        icon: icons.LayoutTemplate,
                    },
                    {
                        title: 'Website Methods',
                        href: '/dashboard/website-payment-methods',
                        icon: icons.CreditCard,
                    },
                    {
                        title: 'Website Messages',
                        href: '/dashboard/website-messages',
                        icon: icons.MessageCircle,
                    },
                ],
            },
            {
                title: 'E-Commerce',
                icon: icons.ShoppingCart,
                children: [
                    {
                        title: 'Products',
                        href: '/dashboard/products',
                        icon: icons.Package,
                    },
                    
                ],
            }
        ],
    }
];

export const footerSidebarItems: SidebarSection[] = [
    {
        name: 'Roles & Permissions',
        role: ['super_admin'],
        permission: ['assignments_access'],
        items: [
            {
                title: 'Assignments',
                role: ['super_admin'],
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
                        title: 'User Assignments',
                        href: '/dashboard/assignments/user-assignments',
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
