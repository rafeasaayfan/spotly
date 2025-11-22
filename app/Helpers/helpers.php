<?php

use App\Models\User;
use Carbon\Carbon;

/**
 * Determine the redirect page after login based on the user's permissions.
 */
if (! function_exists('redirectAfterLogin')) {
    function redirectAfterLogin(User $user): string
    {
        return $user->can('dashboard_access')
            ? route('dashboard.index', absolute: false)
            : route('client.dashboard', absolute: false);
    }
}

/**
 * Get human-readable time with days and hours.
 *
 * @param Carbon $date
 * @return string
 */
if (! function_exists('getTimeWithDaysAndHours')) {
    function getTimeWithDaysAndHours(Carbon $date): string
{
    $now = Carbon::now();
    $diff = $now->diff($date);

    $days = $diff->days;
    $hours = $diff->h;

    $parts = [];

    if ($days > 0) {
        $parts[] = $days . ' ' . ($days === 1 ? 'day' : 'days');
    }

    if ($hours > 0) {
        $parts[] = $hours . ' ' . ($hours === 1 ? 'hour' : 'hours');
    }

    // If less than an hour, show minutes
    if (empty($parts) && $diff->i > 0) {
        $parts[] = $diff->i . ' ' . ($diff->i === 1 ? 'minute' : 'minutes');
    }

    // If less than a minute, show seconds
    if (empty($parts)) {
        $parts[] = $diff->s . ' ' . ($diff->s === 1 ? 'second' : 'seconds');
    }

    return implode(' and ', $parts);
    }
}
