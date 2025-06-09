import type { SharedData, NavItem } from '@/types';

import { usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

export function useNavigation(navbarItems: NavItem[]) {
    const page = usePage<SharedData>();
    const activeSection = ref(navbarItems[0].href);

    // Scroll tracking
    const setupScrollTracking = () => {
        const onScroll = () => {
            for (const item of navbarItems) {
                if(!item.href.startsWith('#')) continue;
                const section = document.querySelector(item.href);

                const rect = section?.getBoundingClientRect();
                if(!rect) return;
                if (rect.top <= 100 && rect.bottom >= 100) {
                    activeSection.value = section?.id || '';
                    break;
                }
            }
        };

        window.addEventListener('scroll', onScroll);
        onScroll(); // Initial check

        return () => window.removeEventListener('scroll', onScroll);
    };

    // Navigation utilities
    const scrollToSection = (id: string) => {
        const el = document.querySelector(id);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth' });
        }
    };

    const isCurrentRoute = computed(() => (url: string) => page.url === url);

    const isActiveSection = computed(() => (href: string) => {
        const sectionId = href.startsWith('#') ? href.slice(1) : href;
        return activeSection.value === sectionId;
    });

    const activeNavStyle = (href: string, baseClass: string = '') => {
        if (href.startsWith('#')) {
            return isActiveSection.value(href) ? `${baseClass} bg-content-3-active text-active` : `${baseClass} bg-content-3`;
        } else {
            return isCurrentRoute.value(href) ? `${baseClass} bg-content-3-active text-active` : `${baseClass} bg-content-3`;
        }
    };

    return {
        activeSection,
        setupScrollTracking,
        scrollToSection,
        isCurrentRoute,
        isActiveSection,
        activeNavStyle,
    };
}
