import { Auth } from '@/types';
import { usePage } from '@inertiajs/vue3';

export default function useAuth() {
    const page = usePage();

    const auth = page.props.auth as Auth;

    const user = auth.user;
    const roles = auth.roles || [];
    const permissions = auth.permissions || [];
    const websiteUserRole = auth.websiteUserRole || [];

    const hasRole = (role: string) => roles.includes(role);
    const hasAnyRole = (rolesToCheck: string[]) => rolesToCheck.some((r: string) => roles.includes(r));

    const can = (permission: string) => permissions.includes(permission);
    const canAny = (permissionsToCheck: string[]) => permissionsToCheck.some((p: string) => permissions.includes(p));

    const websiteHasRole = (role: string) => websiteUserRole.includes(role);
    const websiteHasAnyRole = (rolesToCheck: string[]) => rolesToCheck.some((r: string) => websiteUserRole.includes(r));

    return {
        user,
        roles,
        permissions,
        hasRole,
        hasAnyRole,
        can,
        canAny,
        websiteHasRole,
        websiteHasAnyRole,
    };
}
