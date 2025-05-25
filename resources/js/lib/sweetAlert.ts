import Swal from 'sweetalert2';
import { computed } from 'vue';

const isDark = computed(() => document.documentElement.classList.contains('dark'));

// SweetAlert confirm dialog
export const confirmDialog = (options = {}) => {
    return Swal.fire({
        title: 'Are you sure?',
        icon: 'warning',

        background: isDark.value ? 'hsl(0 0% 4%)' : 'hsl(0 0% 90%)',
        confirmButtonColor: isDark.value ? 'hsl(355, 78%, 56%)' : 'hsl(0 72% 51%)',
        cancelButtonColor: isDark.value ? 'hsl(220 60% 50%)' : 'hsl(216 80% 30%)',

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

    // background: '#1e293b', // dark
    background: isDark.value ? 'hsl(0 0% 4%)' : 'hsl(0 0% 90%)',
    color: isDark.value ? '#fff' : '#000',

    customClass: {
        popup: 'popup',
    },

    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    },
});
