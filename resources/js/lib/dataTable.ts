// import { ref, computed } from 'vue';

// For display or hide the table options
export interface TableConditions {
    enableSearch?: boolean;
    enableSorting?: boolean;
    enablePagination?: boolean;
    enableLimit?: boolean;
    enableFilter?: boolean;
    enableColsVisible?: boolean;

    enableEdit?: boolean;
    enableDelete?: boolean;
    enableCreate?: boolean;
    enableView?: boolean;
    enableRowsDelete?: boolean;

    enableAssignRoles?: boolean;
    enableAssignPermissions?: boolean;
    enableUserAssignments?: boolean;
}
// The default options
export const defaultTableConditions: TableConditions = {
    enableSearch: true,
    enableSorting: true,
    enablePagination: true,
    enableLimit: true,
    enableFilter: true,
    enableColsVisible: true,

    enableEdit: true,
    enableDelete: true,
    enableCreate: true,
    enableView: true,
    enableRowsDelete: true,

    enableAssignRoles: false,
    enableAssignPermissions: false,
    enableUserAssignments: false,
};

export const formatters = {
    // Boolean formatter (Yes/No)
    boolean: (value: any) =>
        value
            ? `<span class="bg-success text-for-bg-success px-2 py-1 rounded text-xs">Yes</span>`
            : `<span class="bg-destructive text-for-bg-destructive px-2 py-1 rounded text-xs">No</span>`,

    // Active formatter (Yes/No)
    active: (value: any) =>
        value
            ? `<span class="bg-success text-for-bg-success px-2 py-1 rounded text-xs border border-muted shadow">Active</span>`
            : `<span class="bg-destructive text-for-bg-destructive px-2 py-1 rounded text-xs border border-muted shadow">Inactive</span>`,

    // Status formatter with color
    status: (value: string) => {
        const colorMap: Record<string, string> = {
            active: 'bg-primary text-for-bg-primary',
            inactive: 'bg-destructive text-for-bg-destructive',

            approved: 'bg-success text-for-bg-success',
            accepted: 'bg-success text-for-bg-success',
            completed: 'bg-success text-for-bg-success',
            comfirmed: 'bg-success text-for-bg-success',

            'not verified': 'bg-destructive text-for-bg-destructive',
            verified: 'bg-success text-for-bg-success',

            delivered: 'bg-primary text-for-bg-primary',

            pending: 'bg-content text-body-active border border-muted shadow',

            blocked: 'bg-destructive text-for-bg-destructive',
            denied: 'bg-destructive text-for-bg-destructive',
            rejected: 'bg-destructive text-for-bg-destructive',
            banned: 'bg-destructive text-for-bg-destructive',
            failed: 'bg-destructive text-for-bg-destructive',
            refunded: 'bg-destructive text-for-bg-destructive',
            cancelled: 'bg-destructive text-for-bg-destructive',
        };

        const color = colorMap[value.toLowerCase()] || 'bg-content border border-muted shadow';

        return `<span class="${color} px-2 py-1 rounded text-xs">${value}</span>`;
    },

    // Email verified
    emailVerified: (value: string | null) => {
        if (!value) {
            return `<span class="bg-destructive text-for-bg-destructive rounded text-xs px-2 py-1">Not Verified</span>`;
        } else {
            return `<span class="bg-success text-for-bg-success rounded text-xs px-2 py-1">Verified</span>`;
        }
    },

    // Date formatter
    date: (value: string | null, format: string = 'short') => {
        if (!value) return '';
        const date = new Date(value);

        if (format === 'short') return date.toLocaleDateString();
        if (format === 'long') return date.toLocaleString();
        if (format === 'time') return date.toLocaleTimeString();

        return date.toLocaleString();
    },

    // Color formatter
    color: (value: string) => {
        return `
            <div class="flex items-center gap-2">
                <span class="size-4 rounded-full border border-muted shadow" 
                style="background-color: ${value}"></span>
                <span class="text-xs">${value}</span>
            </div>
        `;
    },

    // Currency formatter
    currency: (value: number, currency: string = 'USD') => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: currency,
        }).format(value);
    },

    shouldSplit: (key: string, value: string): boolean => {
        // 1. Find the index of the LAST underscore
        const lastUnderscoreIndex = key.lastIndexOf('_');

        if (lastUnderscoreIndex === -1 || lastUnderscoreIndex === 0 || lastUnderscoreIndex === key.length - 1) {
            return false;
        }

        const prefix = key.substring(0, lastUnderscoreIndex);

        const isLikelyPlural = prefix.endsWith('s') || prefix.endsWith('es') || prefix.endsWith('ies');

        return isLikelyPlural || (typeof value === 'string' && value.includes(',')) || Array.isArray(value);
    },

    splitAndStyle: (value: string): string[] => {
        if (typeof value === 'string') {
            return value
                .split(',')
                .map((v) => v.trim())
                .filter(Boolean);
        }

        return [];
    },
};
