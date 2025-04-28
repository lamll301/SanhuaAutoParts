
const API_BASE = process.env.VUE_APP_API_BASE_URL;
const EXTERNAL_API = {
    provinces: 'https://provinces.open-api.vn/api',
}

const resources = [
    'permissions', 'roles', 'users', 'units', 'suppliers',
    'vouchers', 'promotions', 'articles', 'categories', 'products',
    'locations', 'inventories', 'orders', 'carts',
    'imports', 'exports', 'disposals', 'check', 'cancel'
];

// ============ BASE SERVICES ============
class UrlBuilder {
    constructor(baseUrl) {
        this.baseUrl = baseUrl;
        this.queryParams = new URLSearchParams();
        this.options = {};
    }

    withParams(params = {}) {
        Object.entries(params).forEach(([key, value]) => {
            this.queryParams.delete(key);

            if (value === undefined && value === null) {
                return;
            }
            
            this.queryParams.append(key, value);
        });
        return this;
    }

    trashed() {
        this.options.isTrash = true;
        return this;
    }

    all() {
        this.queryParams.append('all', 'true');
        return this;
    }

    build() {
        const endpoint = this.options.isTrash ? `${this.baseUrl}/trashed` : this.baseUrl;
        const queryString = this.queryParams.toString();
        return queryString ? `${endpoint}?${queryString}` : endpoint;
    }

    toString() {
        return this.build();
    }

    valueOf() {
        return this.toString();
    }
}

const getBaseResourceServices = (resource) => {
    const baseUrl = `${API_BASE}/api/${resource}`;
    
    return {
        get: (params = {}) => {
            const builder = new UrlBuilder(baseUrl);
            if (Object.keys(params).length > 0) {
                builder.withParams(params);
            }
            return builder;
        },
        view: (id) => `${baseUrl}/${id}`,
        create: () => baseUrl,
        update: (id) => `${baseUrl}/${id}`,
        delete: (id) => `${baseUrl}/${id}`,
        restore: (id) => `${baseUrl}/${id}/restore`,
        forceDelete: (id) => `${baseUrl}/${id}/force-delete`,
        handleActions: () => `${baseUrl}/handle-form-actions`
    };
};

// ============ SPECIAL RESOURCE HANDLERS ============
const getRoleSpecificServices = (resource) => ({
    addPermission: (id) => `${API_BASE}/api/${resource}/${id}/permission`,
    removePermission: (id, permissionId) => `${API_BASE}/api/${resource}/${id}/permission/${permissionId}`,
});

// ============ RESOURCE SERVICE FACTORY ============
const resourceServices = resources.reduce((services, resource) => {
    services[resource] = {
        ...getBaseResourceServices(resource),
        ...(resource === 'roles' ? getRoleSpecificServices(resource) : {}),
    };
    return services;
}, {});

// ============ EXTERNAL SERVICES ============
const externalServices = {
    getAllCities: () => `${EXTERNAL_API.provinces}/p/`,
    getAllDistricts: () => `${EXTERNAL_API.provinces}/d/`,
    getAllWards: () => `${EXTERNAL_API.provinces}/w/`,
    getDistrictsById: (id) => `${EXTERNAL_API.provinces}/p/${id}?depth=2`,
    getWardsById: (id) => `${EXTERNAL_API.provinces}/d/${id}?depth=2`,
    
    provinces: {
        getAll: () => `${EXTERNAL_API.provinces}/p/`,
        getDistricts: (provinceId) => `${EXTERNAL_API.provinces}/p/${provinceId}?depth=2`,
    },
    districts: {
        getAll: () => `${EXTERNAL_API.provinces}/d/`,
        getWards: (districtId) => `${EXTERNAL_API.provinces}/d/${districtId}?depth=2`,
    },
    wards: {
        getAll: () => `${EXTERNAL_API.provinces}/w/`,
    }
};


export default {
    ...resourceServices,
    ...externalServices
};
