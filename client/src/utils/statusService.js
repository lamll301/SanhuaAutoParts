
const STATUS_CONFIG = Object.freeze({
    user: { 0: 'Không hoạt động', 1: 'Hoạt động', 2: 'Bị cấm' },
    product: { 0: 'Không hoạt động', 1: 'Hoạt động', 2: 'Hết hàng', },
    article: { 0: 'Không hoạt động', 1: 'Hoạt động', },
    voucher: { 0: 'Không hoạt động', 1: 'Hoạt động', },
    promotion: { 0: 'Bản nháp', 1: 'Hoạt động', 2: 'Lên lịch', 3: 'Hết hạn', 4: 'Bị hủy' },
    location: { 0: 'Còn trống', 1: 'Đã đầy', 2: 'Tạm khóa' },
})

class StatusService {
    constructor(config) {
        this.config = config
    }

    getLabel(type, value) {
        return this.config[type]?.[value]
    }

    getOptions(type) {
        return Object.entries(this.config[type] || {}).map(([value, label]) => ({
            value: parseInt(value),
            label
        }))
    }
}

export const statusService = new StatusService(STATUS_CONFIG)