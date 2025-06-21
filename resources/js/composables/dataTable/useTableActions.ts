import { confirmDialog, toast } from '@/lib/sweetAlert';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

export function useTableActions(selectedIds = ref<number[]>([]), routeName: string) {
    async function handleAction(
        action: 'delete' | 'edit' | 'view' | 'assignRoles' | 'assignPermissions' | 'userAssignments',
        idOrIds: number | number[],
    ) {
        switch (action) {
            case 'delete': {
                const ids = Array.isArray(idOrIds) ? idOrIds : [idOrIds];

                if (!ids.length) {
                    toast.fire({ icon: 'error', title: 'Please select items to delete!' });
                    return;
                }

                const result = await confirmDialog({ text: 'Delete selected items?' });

                if (!result.isConfirmed) return;

                router.post(
                    route(`${routeName}.destroy`),
                    { ids },
                    {
                        preserveScroll: true,
                        preserveState: true, // Preserve current state including filters
                        onSuccess: () => {
                            selectedIds.value = [];
                        },
                        onError: (errors) => {
                            console.error('Deletion failed:', errors);
                        },
                    },
                );
                break;
            }

            // case 'edit': {
            //     if (typeof idOrIds !== 'number') {
            //         toast.fire({ icon: 'error', title: 'Edit action requires a single ID!' });
            //         return;
            //     }

            //     router.get(route(`${routeName}.edit`, idOrIds));
            //     break;
            // }

            // case 'view': {
            //     if (typeof idOrIds !== 'number') {
            //         toast.fire({ icon: 'error', title: 'View action requires a single ID!' });
            //         return;
            //     }

            //     router.get(route(`${routeName}.show`, idOrIds));
            //     break;
            // }

            // case 'assignRoles': {
            //     if (typeof idOrIds !== 'number') {
            //         toast.fire({ icon: 'error', title: 'Assign roles action requires a single ID!' });
            //         return;
            //     }

            //     router.get(route(`${routeName}.assignRoles`, idOrIds));
            //     break;
            // }

            // case 'assignPermissions': {
            //     if (typeof idOrIds !== 'number') {
            //         toast.fire({ icon: 'error', title: 'Assign permissions action requires a single ID!' });
            //         return;
            //     }

            //     router.get(route(`${routeName}.assignPermissions`, idOrIds));
            //     break;
            // }

            // case 'usersAssignments': {
            //     if (typeof idOrIds !== 'number') {
            //         toast.fire({ icon: 'error', title: 'Assign permissions action requires a single ID!' });
            //         return;
            //     }

            //     router.get(route(`${routeName}.assignment`, idOrIds));
            //     break;
            // }

            default:
                console.warn(`Unknown action: ${action}`);
        }
    }

    return { handleAction };
}
