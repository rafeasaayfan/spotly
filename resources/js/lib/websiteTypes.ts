import { icons } from 'lucide-vue-next';

export const typeColor = {
    bg: (type: string) => {
        switch (type.toLowerCase()) {
            case 'e-commerce':
                return 'bg-emerald-600 text-white';
            case 'restaurant':
                return 'bg-orange-500 text-white';
            case 'portfolio':
                return 'bg-purple-600 text-white';
            case 'blog-news':
                return 'bg-blue-600 text-white';
            case 'clinic':
                return 'bg-cyan-600 text-white';
            case 'gym':
                return 'bg-red-600 text-white';
            case 'hotel':
                return 'bg-amber-600 text-white';
            case 'lawyer':
                return 'bg-indigo-700 text-white';
            default:
                return 'bg-gray-200 text-gray-800';
        }
    },

    text: (type: string) => {
        switch (type.toLowerCase()) {
            case 'e-commerce':
                return 'text-emerald-600';
            case 'restaurant':
                return 'text-orange-500';
            case 'portfolio':
                return 'text-purple-600';
            case 'blog-news':
                return 'text-blue-600';
            case 'clinic':
                return 'text-cyan-600';
            case 'gym':
                return 'text-red-600';
            case 'hotel':
                return 'text-amber-600';
            case 'lawyer':
                return 'text-indigo-700';
            default:
                return 'text-gray-200';
        }
    },
};

export const typeIcon = (type: string) => {
    switch (type.toLowerCase()) {
        case 'e-commerce':
            return icons.ShoppingCart;
        case 'restaurant':
            return icons.UtensilsCrossed;
        case 'portfolio':
            return icons.Brush;
        case 'blog-news':
            return icons.Newspaper;
        case 'clinic':
            return icons.Stethoscope;
        case 'gym':
            return icons.Dumbbell;
        case 'hotel':
            return icons.Hotel;
        case 'lawyer':
            return icons.Gavel;
        default:
            return icons.CircleHelp;
    }
}
