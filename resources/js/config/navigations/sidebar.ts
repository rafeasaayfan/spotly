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
                icon: icons.LayoutDashboard,
            },
            {
                title: 'My Websites',
                href: '/dashboard/my-websites',
                icon: icons.Globe,
            },
            {
                title: 'Payments',
                icon: icons.CreditCard,
                children: [
                    {
                        title: 'Overview',
                        href: '/dashboard/my-payments',
                        icon: icons.ChartPie,
                    },
                    {
                        title: 'Make a Payment',
                        href: '/dashboard/make-payment',
                        icon: icons.Send,
                    },
                ],
            },
        ],
    },
    {
        name: 'Spotly',
        permission: ['dashboard_access'],
        items: [
            {
                title: 'Users',
                href: '/dashboard/users',
                icon: icons.Users,
            },
            {
                title: 'Administration',
                icon: icons.Settings,
                children: [
                    {
                        title: 'Website Types',
                        href: '/dashboard/website-types',
                        icon: icons.Type,
                    },
                    {
                        title: 'Countries',
                        href: '/dashboard/countries',
                        icon: icons.Flag,
                    },
                    {
                        title: 'Messages',
                        href: '/dashboard/messages',
                        icon: icons.MessageCircle,
                    },
                    {
                        title: 'Email Subscribers',
                        href: '/dashboard/email-subscribers',
                        icon: icons.Mail,
                    },
                ],
            },
            {
                title: 'UI Templates',
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
            {
                title: 'Subscriptions',
                icon: icons.Wallet,
                children: [
                    {
                        title: 'Overview',
                        href: '/dashboard/subscriptions',
                        icon: icons.ChartPie,
                    },
                    {
                        title: 'Plans',
                        href: '/dashboard/plans',
                        icon: icons.Layers,
                    },
                    {
                        title: 'Payment Methods',
                        href: '/dashboard/payment-methods',
                        icon: icons.Banknote,
                    },
                    {
                        title: 'Payments',
                        href: '/dashboard/payments',
                        icon: icons.CreditCard,
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
                title: 'All Websites',
                href: '/dashboard/websites',
                icon: icons.Monitor,
            },
        ],
    },
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
