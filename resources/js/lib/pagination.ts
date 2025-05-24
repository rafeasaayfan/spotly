export interface PaginationLink {
    url: string | null; // URL for the link, or null if there is no link
    label: string; // Text to display for the link
    active: boolean; // Whether the link is active (i.e., the current page)
}

export interface PaginationData {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
    data: any[];
    links: PaginationLink[];
}

export function formatPaginationLinks(links: PaginationLink[]): PaginationLink[] {
    if (!links || links.length === 0) return [];

    const result: PaginationLink[] = [];

    const prev = links.find((l) => l.label.includes('Previous') || l.label === '‹');
    const next = links.find((l) => l.label.includes('Next') || l.label === '›');

    // Add Previous
    if (prev?.url) result.push(prev);

    const numbered = links.filter((l) => !l.label.includes('Previous') && !l.label.includes('Next') && l.label !== '‹' && l.label !== '›');
    result.push(...numbered);

    // Add Next
    if (next?.url) result.push(next);

    return result;
}

/**
 * Generate pagination links following Laravel Blade template logic
 */
export function generateBladeStylePagination(data: PaginationData | null | undefined, existingLinks: PaginationLink[]): PaginationLink[] {
    if (!data) return [];

    const hasPages = (data.last_page || 1) > 1;
    if (!hasPages) return [];

    const currentPage = data.current_page || 1;
    const lastPage = data.last_page || 1;
    const onFirstPage = currentPage === 1;
    const hasMorePages = currentPage < lastPage;

    const links: PaginationLink[] = [];

    const prev = existingLinks.find((l) => l.label.includes('Previous') || l.label === '‹');
    const next = existingLinks.find((l) => l.label.includes('Next') || l.label === '›');

    if (!onFirstPage && prev?.url) {
        links.push(prev);
    }

    const visibleWindow = 4;
    const start = Math.max(currentPage - 2, 1);
    const end = Math.min(start + visibleWindow - 1, lastPage);
    const adjustedStart = Math.max(Math.min(start, lastPage - visibleWindow + 1), 1);

    // Show first page
    if (adjustedStart > 1) {
        links.push({
            url: generatePageUrl(1),
            label: '1',
            active: currentPage === 1,
        });

        if (adjustedStart > 2) {
            links.push({
                url: null,
                label: '...',
                active: false,
            });
        }
    }

    // Main range (don’t skip 1 unless already added)
    for (let i = adjustedStart; i <= end; i++) {
        if ((i === 1 && adjustedStart > 1) || (i === lastPage && end < lastPage)) continue;

        links.push({
            url: i === currentPage ? null : generatePageUrl(i),
            label: i.toString(),
            active: i === currentPage,
        });
    }

    // Show last page
    if (end < lastPage) {
        if (end < lastPage - 1) {
            links.push({
                url: null,
                label: '...',
                active: false,
            });
        }

        links.push({
            url: generatePageUrl(lastPage),
            label: lastPage.toString(),
            active: currentPage === lastPage,
        });
    }

    if (hasMorePages && next?.url) {
        links.push(next);
    }

    return links;
}

/**
 * Generate URL for a specific page
 */
function generatePageUrl(page: number): string {
    const url = new URL(window.location.href);
    url.searchParams.set('page', page.toString());
    return url.toString();
}
