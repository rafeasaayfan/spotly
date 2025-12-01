import { type NavItem } from '@/types';
import { Home, ListOrdered, ShoppingBag, Truck } from 'lucide-vue-next';

export const navbarItems: NavItem[] = [
    {
        title: 'nav.home',
        href: '/',
        icon: Home,
    },
    {
        title: 'nav.shop',
        href: '/shop',
        icon: ShoppingBag,
    },
    {
        title: 'nav.my.orders',
        href: '/orders',
        icon: ListOrdered,
    },
    {
        title: 'nav.trackOrder',
        href: '/track-order',
        icon: Truck,
    },
];
