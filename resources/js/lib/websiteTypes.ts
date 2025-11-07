export const typeColor = {
    bg: (type: string) => {
        switch (type) {
            case 'e-commerce':
                return 'bg-emerald-600 text-white';
            case 'restaurant':
                return 'bg-orange-500 text-white';
            case 'portfolio':
                return 'bg-purple-500 text-white';
            case 'blog-news':
                return 'bg-pink-500 text-white';
            case 'clinic':
                return 'bg-teal-600 text-white';
            case 'gym':
                return 'bg-lime-600 text-white';
            case 'hotel':
                return 'bg-cyan-600 text-white';
            case 'lawyer':
                return 'bg-indigo-700 text-white';
            default:
                return 'bg-gray-200 text-gray-800';
        }
    },

    text: (type: string) => {
        switch (type) {
            case 'e-commerce':
                return 'text-emerald-600';
            case 'restaurant':
                return 'text-orange-500';
            case 'portfolio':
                return 'text-purple-500';
            case 'blog-news':
                return 'text-pink-500';
            case 'clinic':
                return 'text-teal-600';
            case 'gym':
                return 'text-lime-600';
            case 'hotel':
                return 'text-cyan-600';
            case 'lawyer':
                return 'text-indigo-700';
            default:
                return 'text-gray-200';
        }
    },
};
