import { type NavItem } from '@/types';
import { Home } from 'lucide-vue-next';

export const navbarItems: NavItem[] = [
    {
        title: 'Home',
        href: '/home',
        icon: Home,
    },
    {
        title: 'About',
        href: '/about',
    },
    {
        title: 'Contact',
        href: '/contact',
    },
];
