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
    {
        name: 'Spotly',
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
                        href: '/dashboard/websiteTypes',
                        icon: icons.Type,
                    },
                    {
                        title: 'Countries',
                        href: '/dashboard/countries',
                        icon: icons.Flag,
                    },
                    {
                        title: 'Payment Methods',
                        href: '/dashboard/paymentMethods',
                        icon: icons.CreditCard,
                    },
                    {
                        title: 'Messages',
                        href: '/dashboard/messages',
                        icon: icons.MessageCircle,
                    },
                    {
                        title: 'Email subscribers',
                        href: '/dashboard/emailSubscribers',
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
                        href: '/dashboard/ui/templateColors',
                    },
                    {
                        title: 'Template Template Colors',
                        href: '/dashboard/ui/templateTemplateColors',
                    },
                ],
            },
        ],
    },
    {
        name: 'Websites',
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
                        href: '/dashboard/websiteUsers',
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
                        href: '/dashboard/websiteTemplates',
                        icon: icons.LayoutTemplate,
                    },
                    {
                        title: 'Website Methods',
                        href: '/dashboard/websitePaymentMethods',
                        icon: icons.CreditCard,
                    },
                    {
                        title: 'Website Messages',
                        href: '/dashboard/websiteMessages',
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
        permission: 'assignments_access',
        items: [
            {
                title: 'Assignments',
                permission: 'assignments_access',
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

//? Sidebar UI
export type SidebarVariant = 'sidebar' | 'floating' | 'inset';
export type sidebarCollapsible = 'offcanvas' | 'icon' | 'none';

export const sidebarVariant: SidebarVariant = 'sidebar';
export const sidebarCollapsible: sidebarCollapsible = 'icon';
