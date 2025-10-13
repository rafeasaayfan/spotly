<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $locale = session('locale', app()->getLocale());

        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        $roles = [];
        $permissions = [];

        $websiteUserRole = null;

        if ($request->user('web')) {
            if (method_exists($request->user('web'), 'getRoleNames')) {
                $roles = $request->user('web')->getRoleNames();
                $permissions = $request->user('web')->getAllPermissions()->pluck('name');
            }
        } else if ($request->user('website')) {
            $websiteUser = \App\Models\WebsiteUser::where('id', $request->user('website')->id)
                ->first();

            if($websiteUser) $websiteUserRole = $websiteUser->role;
        }

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $request->user('web'),
                'website_user' => $request->user('website'),
                'roles' => $roles,
                'permissions' => $permissions,
                'websiteUserRole' => $websiteUserRole
            ],
            'ziggy' => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'lang' => $locale,
            'flash' => [
                'message' => fn() => $request->session()->get('message'),
                'success' => fn() => $request->session()->get('success'),
                'toastType' => fn() => $request->session()->get('toastType'),
            ],
        ];
    }
}
