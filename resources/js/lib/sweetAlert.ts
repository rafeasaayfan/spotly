import Swal from 'sweetalert2';

// SweetAlert confirm dialog
export const confirmDialog = (options = {}) => {
    return Swal.fire({
        title: 'Are you sure?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, do it!',
        ...options,
    });
};

// Toast config
export const toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    timer: 3000,
    showConfirmButton: false,
    //   timerProgressBar: true,
    background: '#1e293b', // dark
    color: '#f8fafc', // text
    // didOpen: (toast) => {
    //     toast.onmouseenter = Swal.stopTimer;
    //     toast.onmouseleave = Swal.resumeTimer;
    // },
});
