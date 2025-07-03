import { type NavItem } from '@/types';
import { Home, Mail, MoveDown, ShoppingBag, Users } from 'lucide-vue-next';

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
    {
        title: 'About',
        href: '#about',
        icon: Users,
    },
    {
        title: 'Contact',
        href: '#contact',
        icon: Mail,
    },
];
