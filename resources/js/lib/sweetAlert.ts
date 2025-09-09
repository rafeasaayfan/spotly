import Swal from 'sweetalert2';
import { computed } from 'vue';

const isDark = computed(() => document.documentElement.classList.contains('dark'));

// SweetAlert confirm dialog
export const confirmDialog = (options = {}) => {
    return Swal.fire({
        title: 'Are you sure?',
        icon: 'warning',

        background: isDark.value ? 'hsl(0 0% 4%)' : 'hsl(0 0% 96%)',
        confirmButtonColor: isDark.value ? 'hsl(0, 74%, 45%)' : 'hsl(0 80% 50%)',
        cancelButtonColor: isDark.value ? 'hsl(221, 83%, 45%)' : 'hsl(221 80% 40%)',

        color: isDark.value ? '#fff' : '#000',

        showCancelButton: true,
        confirmButtonText: 'Yes, do it!',

        customClass: {
            popup: 'popup',
        },

        ...options,
    });
};

// Toast config
export const toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    timer: 3000,
    showConfirmButton: false,
    timerProgressBar: true,
    width: 'fit-content',

    // background: '#1e293b', // dark
    background: isDark.value ? 'hsl(0 0% 4%)' : 'hsl(0 0% 96%)',
    color: isDark.value ? '#fff' : '#000',

    customClass: {
        popup: 'popup',
        icon: 'my-toast-icon'
    },

    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    },
});
