
const API_BASE = process.env.VUE_APP_API_BASE_URL;
const EXTERNAL_API = {
    provinces: 'https://provinces.open-api.vn/api',
}

const resources = [
    'permissions',
    'roles',
    'users',
    'units',
    'suppliers',
    'vouchers',
    'promotions',
    'articles',
    'categories',
    'products',
    'imports',
    'exports',
    'inventories',
    'orders',
    'carts',
];


// ============ BASE SERVICES ============
const getBaseResourceServices = (resource) => ({
    get: (params = {}, isTrash = false, isAll = false) => {
        const endpoint = isTrash ? `/${resource}/trashed` : `/${resource}`;
        const queryParams = new URLSearchParams({ ...params });
        if (isAll) queryParams.append('all', 'true');
        return `${API_BASE}/api${endpoint}?${queryParams.toString()}`;
    },
    view: (id) => `${API_BASE}/api/${resource}/${id}`,
    create: () => `${API_BASE}/api/${resource}`,
    update: (id) => `${API_BASE}/api/${resource}/${id}`,
    delete: (id) => `${API_BASE}/api/${resource}/${id}`,
    restore: (id) => `${API_BASE}/api/${resource}/${id}/restore`,
    forceDelete: (id) => `${API_BASE}/api/${resource}/${id}/force-delete`,
    handleActions: () => `${API_BASE}/api/${resource}/handle-form-actions`
});

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
};


export default {
    ...resourceServices,
    ...externalServices
};
