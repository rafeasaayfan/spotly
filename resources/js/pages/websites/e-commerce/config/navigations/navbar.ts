import { type NavItem } from '@/types';
import { Home, ShoppingBag } from 'lucide-vue-next';

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
];
