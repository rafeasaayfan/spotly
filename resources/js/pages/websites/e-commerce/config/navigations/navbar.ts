import { type NavItem } from '@/types';
import { Home, MoveDown, ShoppingBag } from 'lucide-vue-next';

export const navbarItems: NavItem[] = [
    {
        title: 'Home',
        href: '/',
        icon: Home,
    },
    {
        title: 'Shop',
        href: '/shop',
        icon: ShoppingBag,
    },
    {
        title: 'Offers',
        href: '/offers',
        icon: MoveDown,
    },
];
