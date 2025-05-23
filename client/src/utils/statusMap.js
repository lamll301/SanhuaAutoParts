
const STATUS_CONFIG = Object.freeze({
    location: { 
        0: 'Còn trống', 
        1: 'Đang chứa hàng', 
        2: 'Đã đầy' 
    },
    product: { 
        0: 'Hoạt động', 
        1: 'Ngưng bán', 
    },
    user: { 
        0: 'Bị cấm', 
        1: 'Hoạt động' 
    },
    promotion: { 
        0: 'Sắp diễn ra',
        1: 'Đang áp dụng', 
        2: 'Hết hạn',
    },
    order: { 
        0: 'Đang xử lý',
        1: 'Đang đóng gói',
        2: 'Đang giao hàng',
        3: 'Đã giao hàng',
        4: 'Đã hủy'
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
