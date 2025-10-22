import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
    website_user: User;
    roles: string[];
    permissions: string[];
    websiteUserRole: string;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href?: string;
    icon?: LucideIcon;
    isActive?: boolean;
    children?: NavItem[];
    role?: string[];
    permission?: string[];
}

export interface SidebarSection {
    name: string;
    items: NavItem[];
    role?: string[];
    permission?: string[];
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
    lang: 'en' | 'ar'
}

export interface User {
    id: number;
    name: string;
    email: string;
    phone_number?: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Filter {
    key: string;
    label: string;
    type: string;
    placeholder?: string;
    options?: Array<{value: string, label: string}>;
}

export type BreadcrumbItemType = BreadcrumbItem;
