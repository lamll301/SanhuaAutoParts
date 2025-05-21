
const STATUS_CONFIG = Object.freeze({
    user: { 0: 'Không hoạt động', 1: 'Hoạt động', 2: 'Bị cấm' },
    product: { 0: 'Không hoạt động', 1: 'Hoạt động', 2: 'Hết hàng', },
    promotion: { 0: 'Bản nháp', 1: 'Hoạt động', 2: 'Lên lịch', 3: 'Hết hạn', 4: 'Bị hủy' },
    location: { 0: 'Còn trống', 1: 'Đã đầy', 2: 'Tạm khóa' },
    order: { 
        0: 'Mới tạo', 
        1: 'Đóng gói', 
        2: 'Vận chuyển', 
        3: 'Thành công', 
        4: 'Bị hủy' 
    },
    payment: { 
        0: 'Chưa TT', 
        1: 'Đã TT' 
    }
})

const getStatusText = (type, value) => {
    return STATUS_CONFIG[type]?.[value]
}

const getAllStatusOptions = (type) => {
    return STATUS_CONFIG[type];
};

export { getStatusText, getAllStatusOptions }
