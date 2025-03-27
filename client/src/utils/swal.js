import Swal from 'sweetalert2';

export function swalConfirm(title, text, icon, confirmButtonText) {
    return Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: confirmButtonText
    })
}

export function swalMixin(icon, title) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        },
    });
    Toast.fire({
        icon: icon,
        title: title,
        padding: '1em',
    });
}
export function swalFire(title, text, icon) {
    return Swal.fire({
        title: title,
        text: text,
        icon: icon,
    });
}
