export interface PaginationLink {
    url: string | null; // URL for the link, or null if there is no link
    label: string; // Text to display for the link
    active: boolean; // Whether the link is active (i.e., the current page)
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
