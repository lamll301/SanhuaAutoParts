import Swal from 'sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

const customStyle = document.createElement('style')
customStyle.textContent = `
    .swal2-popup {
        width: 32em !important;
        font-size: 1.2rem !important;
    }
    .swal2-title {
        font-size: 1.8em !important;
    }
    .swal2-content {
        font-size: 1.2em !important;
    }
    .swal2-confirm, .swal2-cancel {
        font-size: 1.1em !important;
        padding: 0.6em 2em !important;
    }
`
document.head.appendChild(customStyle)

export const swal = {
    fire(title, text, icon, params = {}) {
        return Swal.fire({
            title: title,
            text: text,
            icon: icon,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            width: '36em',
            ...params
        });
    },
    loading() {
        return this.fire('Đang tải dữ liệu...', 'Vui lòng chờ trong giây lát.', 'info', {
            allowOutsideClick: false,
            width: '26em',
            didOpen: () => {
                Swal.showLoading();
            }
        });
    },
    closeLoading() {
        Swal.close()
    },
    async withLoading(promise) {
        this.loading()
        try {
            const result = await promise
            this.closeLoading()
            return result
        } catch (error) {
            this.closeLoading()
            throw error
        }
    },
    showValidationMessage(message) {
        return Swal.showValidationMessage(message);
    }
}

export default {
    install: (app) => {
        app.config.globalProperties.$swal = swal
        app.provide('swal', swal)
    }
}