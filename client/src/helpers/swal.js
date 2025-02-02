
import Swal from 'sweetalert2';

export function swalMixin(icon, title) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        },
    });
    Toast.fire({
        icon: icon,
        title: `<span style="font-size: 1.2em;">${title}</span>`,
        padding: '2em',
    });
}
export function swalFire(title, text, icon) {
    return Swal.fire({
        title: title,
        text: text,
        icon: icon,
    });
}
