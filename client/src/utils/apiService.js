
const EXTERNAL_API = {
    provinces: 'https://provinces.open-api.vn/api',
}

const resources = [
    'permissions',
    'roles',
    'users',
];

// ============ CORE API PATHS ============
const resourceAPI = {
    index: (resource, params = {}, isTrash = false, isAll = false) => {
        const endpoint = isTrash ? `/${resource}/trashed` : `/${resource}`;
        const queryParams = new URLSearchParams({ ...params });
        if (isAll) queryParams.append('all', 'true');
        return `${process.env.VUE_APP_API_BASE_URL}/api${endpoint}?${queryParams.toString()}`;
    },
    show: (resource, id) => `${process.env.VUE_APP_API_BASE_URL}/api/${resource}/${id}`,
    store: (resource) => `${process.env.VUE_APP_API_BASE_URL}/api/${resource}`,
    update: (resource, id) => `${process.env.VUE_APP_API_BASE_URL}/api/${resource}/${id}`,
    destroy: (resource, id) => `${process.env.VUE_APP_API_BASE_URL}/api/${resource}/${id}`,
    restore: (resource, id) => `${process.env.VUE_APP_API_BASE_URL}/api/${resource}/${id}/restore`,
    forceDelete: (resource, id) => `${process.env.VUE_APP_API_BASE_URL}/api/${resource}/${id}/force-delete`,
    handleActions: (resource) => `${process.env.VUE_APP_API_BASE_URL}/api/${resource}/handle-form-actions`
}

// ============ BASE SERVICES ============
const getBaseResourceServices = (resource) => ({
    get: (params, isTrash = false, isAll = false) => 
        resourceAPI.index(resource, params, isTrash, isAll),
    view: (id) => resourceAPI.show(resource, id),
    create: () => resourceAPI.store(resource),
    update: (id) => resourceAPI.update(resource, id),
    delete: (id) => resourceAPI.destroy(resource, id),
    restore: (id) => resourceAPI.restore(resource, id),
    forceDelete: (id) => resourceAPI.forceDelete(resource, id),
    handleActions: () => resourceAPI.handleActions(resource),
});

// ============ SPECIAL RESOURCE HANDLERS ============
const getRoleSpecificServices = (resource) => ({
    addPermission: (id) => `${process.env.VUE_APP_API_BASE_URL}/api/${resource}/${id}/permission`,
    removePermission: (id, permissionId) => `${process.env.VUE_APP_API_BASE_URL}/api/${resource}/${id}/permission/${permissionId}`,
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
