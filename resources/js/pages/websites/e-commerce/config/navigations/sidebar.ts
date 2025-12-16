import { type SidebarSection } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { icons } from 'lucide-vue-next';

const page = usePage<{ websiteNameAndLogo?: { name?: string } }>();
const appName = page?.props?.websiteNameAndLogo?.name || '';

//? Sidebar Content
export const mainSidebarItems: SidebarSection[] = [
    {
        name: 'Main',
        websiteRole: ['owner', 'admin'],
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
                websiteRole: ['owner']
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
        websiteRole: ['owner', 'admin'],
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
                title: 'Attributes',
                href: '/dashboard/attributes',
                icon: icons.SlidersHorizontal,
            },
            {
                title: 'Products',
                href: '/dashboard/products',
                icon: icons.Package,
            },
        ],
    },
    {
        name: 'Shop',
        websiteRole: ['owner', 'admin'],
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
            {
                title: 'Track Orders',
                href: '/dashboard/track-orders',
                icon: icons.Route,
            }
        ],
    },
];

export const footerSidebarItems: SidebarSection[] = [
    {
        name: 'Spotly Settings',
        websiteRole: ['owner'],
        items: [
            {
                title: 'Go To Your Website Settings',
                href: `http://127.0.0.1:8000/dashboard/my-websites?search=${encodeURIComponent(appName)}`,
                hrefType: 'a',
                icon: icons.Diamond,
                websiteRole: ['owner'],
            },
        ],
    },
];