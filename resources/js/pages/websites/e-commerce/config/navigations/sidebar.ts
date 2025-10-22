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
        name: 'Catalog',
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
            // {
            //     title: 'Delivery Fees',
            //     href: '/dashboard/delivery-fees',
            //     icon: icons.Truck,
            // },
            {
                title: 'Products',
                href: '/dashboard/products',
                icon: icons.Package,
            },
        ],
    },
    {
        name: 'Shop',
        items: [
            {
                title: 'Orders',
                href: '/dashboard/orders',
                icon: icons.ClipboardList,
            },
            {
                title: 'Carts',
                href: '/dashboard/carts',
                icon: icons.ShoppingCart,
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
                href: 'http://127.0.0.1:8000/dashboard/my-websites',
                icon: icons.Diamond,
            },
        ],
    },
];