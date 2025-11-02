<style>
    svg {
        width: 15px;
        height: 15px;
    }
</style>

<svg viewBox="0 0 80 80" width="80" height="80" preserveAspectRatio="xMidYMid meet" overflow="visible">
    <defs>
        <linearGradient id="ringGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="40%" stop-color="#1650b0" />
            <stop offset="50%" stop-color="#0c449c" />
            <stop offset="60%" stop-color="#0c449c" /> 
            <stop offset="80%" stop-color="#0c449c" />
            <stop offset="100%" stop-color="#ffffff" />
        </linearGradient>

        <filter
            id="ringShadow"
            x="-50%"
            y="-50%"
            width="200%"
            height="200%"
            filterUnits="userSpaceOnUse"
            primitiveUnits="userSpaceOnUse"
        >
            <feDropShadow dx="0" dy="2" stdDeviation="4" flood-color="#1650b0" flood-opacity="0.2" />
        </filter>
    </defs>

    <circle cx="40" cy="40" r="30" stroke="url(#ringGradient)" stroke-width="10" fill="none" filter="url(#ringShadow)" />

    <text x="40" y="50" text-anchor="middle" font-family="Arial, sans-serif" font-size="32" font-weight="bold">
        S
    </text>
</svg>
