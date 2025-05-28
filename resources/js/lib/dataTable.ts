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
    enableUsersAssignments?: boolean;
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
    enableUsersAssignments: false,
};

// Formatters.js أو ضمن نفس ملف lib/composables
export const formatters = {
    // Boolean formatter (Yes/No)
    boolean: (value: any) => (value ? 'Yes' : 'No'),

    // Toggle formatter (active/inactive with icon or color class)
    toggle: (value: boolean) => {
        return value
            ? `<span style="color:green; font-weight:bold">✔ Active</span>`
            : `<span style="color:red; font-weight:bold">✖ Inactive</span>`;
    },

    // Status formatter with color
    status: (value: string) => {
        const colorMap: Record<string, string> = {
            active: 'green',
            inactive: 'gray',
            pending: 'orange',
            blocked: 'red',
        };

        const color = colorMap[value.toLowerCase()] || 'black';

        return `<span style="color:${color}; font-weight:600">${value}</span>`;
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

    EmailVerified: (value: string | null) => {
        if (!value) {
            return `<span class="bg-destructive text-for-bg-destructive rounded-sm text-xs px-2 py-1">Not Verified</span>`;
        } else {
            return `<span class="bg-success text-for-bg-success rounded-sm text-xs px-2 py-1">Verified</span>`;
        }
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

        return isLikelyPlural || ((typeof value === 'string' && value.includes(',')) || Array.isArray(value));
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
