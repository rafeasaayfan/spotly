import { usePage } from '@inertiajs/vue3';

type Auth = {
    user: any;
    roles: string[];
    permissions: string[];
};

export default function useAuth() {
    const page = usePage();

    const auth = page.props.auth as Auth;

    const user = auth.user;
    const roles = auth.roles || [];
    const permissions = auth.permissions || [];

    const hasRole = (role: string) => roles.includes(role);
    const hasAnyRole = (rolesToCheck: string[]) => rolesToCheck.some((r: string) => roles.includes(r));

    const can = (permission: string) => permissions.includes(permission);
    const canAny = (permissionsToCheck: string[]) => permissionsToCheck.some((p: string) => permissions.includes(p));

    return {
        user,
        roles,
        permissions,
        hasRole,
        hasAnyRole,
        can,
        canAny,
    };
}
