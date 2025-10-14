import type { NavItem, SharedData, SidebarSection } from '@/types';

import { usePage } from '@inertiajs/vue3';
import { computed, nextTick, onMounted, ref } from 'vue';

const page = usePage<SharedData>();

export function useNavigation(navbarItems: NavItem[]) {
    const activeSection = ref(navbarItems[0].href);

    // Scroll tracking
    const setupScrollTracking = () => {
        const onScroll = () => {
            for (const item of navbarItems) {
                if (!item.href?.startsWith('#')) continue;
                const section = document.querySelector(item.href);

                const rect = section?.getBoundingClientRect();
                if (!rect) return;
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

    const activeNavStyle = (href: string, activationClass: string = '') => {
        if (href.startsWith('#')) {
            return isActiveSection.value(href) ? `${activationClass ?? 'bg-content-2-active text-active'}` : '';
        } else {
            return isCurrentRoute.value(href) ? `${activationClass ?? 'bg-content-2-active text-active'}` : '';
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

export function useSidebarNavigation(sidebarSection: SidebarSection[]) {
    // Track which dropdowns are open
    const openDropdowns = ref<string[]>([]);

    function isActiveUrl(href: string): boolean {
        if (href === '/dashboard') {
            return href === page.url;
        }

        return href === page.url || page.url.startsWith(href);
    }

    function isHasChildActive(children: Array<{ href?: string }>): boolean {
        return children.some((child) => {
            return child.href === page.url || page.url.startsWith(child.href ?? '');
        });
    }

    function toggleDropdown(title: string) {
        if (openDropdowns.value.includes(title)) {
            openDropdowns.value = openDropdowns.value.filter((t) => t !== title);
        } else {
            openDropdowns.value.push(title);
        }
    }

    function isDropdownOpen(title: string) {
        return openDropdowns.value.includes(title);
    }

    // Initialize openDropdowns on component mount
    onMounted(() => {
        sidebarSection.forEach((section) => {
            section.items.forEach((item) => {
                if (item.children && isHasChildActive(item.children)) {
                    if (!openDropdowns.value.includes(item.title)) {
                        openDropdowns.value.push(item.title);
                    }
                }
            });
        });
    });

    function onEnter(el: Element) {
        const element = el as HTMLElement;

        element.style.height = '0';
        element.style.opacity = '0';
        nextTick(() => {
            element.style.transition = 'all 0.3s ease';
            element.style.height = element.scrollHeight + 'px';
            element.style.opacity = '1';
        });
    }
    function onAfterEnter(el: Element) {
        const element = el as HTMLElement;

        element.style.height = '';
        element.style.opacity = '';
    }
    function onLeave(el: Element) {
        const element = el as HTMLElement;

        element.style.height = el.scrollHeight + 'px';

        // Force reflow to apply the height before collapsing
        void element.offsetHeight;

        nextTick(() => {
            element.style.transition = 'all 0.2s ease';
            element.style.height = '0';
            element.style.opacity = '0';
        });
    }
    function onAfterLeave(el: Element) {
        const element = el as HTMLElement;

        element.style.height = '';
        element.style.opacity = '';
    }

    return {
        isActiveUrl,
        isHasChildActive,
        toggleDropdown,
        isDropdownOpen,
        onEnter,
        onAfterEnter,
        onLeave,
        onAfterLeave
    }
}
