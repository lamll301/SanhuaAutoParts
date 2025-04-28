import Swal from 'sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

export const swal = {
    fire(title, text, icon, params = {}) {
        return Swal.fire({
            title: title,
            text: text,
            icon: icon,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            ...params
        });
    },
    loading() {
        return this.fire('Đang tải dữ liệu...', 'Vui lòng chờ trong giây lát.', 'info', {
            allowOutsideClick: false,
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
}

export default {
    install: (app) => {
        app.config.globalProperties.$swal = swal
        app.provide('swal', swal)
    }
}