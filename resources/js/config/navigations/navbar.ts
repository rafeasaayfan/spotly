import { type NavItem } from '@/types';
import { HelpCircle, Home, PlayCircle, Rocket, Users } from 'lucide-vue-next';

export const navbarItems: NavItem[] = [
    {
        title: 'Hero',
        href: '#hero',
        icon: Home,
    },
    {
        title: 'Why Spotly',
        href: '#why-spotly',
        icon: HelpCircle,
    },
    {
        title: 'How Work',
        href: '#how-it-works',
        icon: PlayCircle,
    },
    {
        title: 'Get Started',
        href: '#get-started',
        icon: Rocket,
    },
    {
        title: 'About Us',
        href: '#about-us',
        icon: Users,
    },
];
