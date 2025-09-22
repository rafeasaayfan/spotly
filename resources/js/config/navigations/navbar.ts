import { type NavItem } from '@/types';
import { HelpCircle, Home, PlayCircle, Rocket, Users } from 'lucide-vue-next';

export const navbarItems: NavItem[] = [
    {
        title: 'nav.hero',
        href: '#hero',
        icon: Home,
    },
    {
        title: 'nav.whySpotly',
        href: '#why-spotly',
        icon: HelpCircle,
    },
    {
        title: 'nav.howWork',
        href: '#how-it-works',
        icon: PlayCircle,
    },
    {
        title: 'nav.getStarted',
        href: '#get-started',
        icon: Rocket,
    },
    {
        title: 'nav.aboutUs',
        href: '#about-us',
        icon: Users,
    },
];
