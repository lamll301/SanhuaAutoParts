
const STATUS_TYPES = Object.freeze({
    USER: 'user',
    PRODUCT: 'product'
});

const STATUS_CONFIG = Object.freeze({
    [STATUS_TYPES.USER]: {
        0: 'Không hoạt động',
        1: 'Hoạt động',
        2: 'Bị cấm'
    },
    [STATUS_TYPES.PRODUCT]: {
        
    }
});

class StatusService {
    constructor(config) {
        this.config = config;
    }

    getLabel(type, value) {
        return this.config[type]?.[value] || 'Không xác định';
    }

    getOptions(type) {
        return Object.entries(this.config[type] || {}).map(([value, label]) => ({
            value: parseInt(value),
            label
        }));
    }
}

export const statusService = new StatusService(STATUS_CONFIG);