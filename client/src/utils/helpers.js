import { format, parseISO } from 'date-fns';
import apiService from './apiService';

export function formatDate(date, pattern = 'yyyy-MM-dd HH:mm:ss') {
    if (!date) return '';
    try {
        return format(parseISO(date), pattern);
    } catch (error) {
        return date;
    }
}

export function formatPrice(price) {
    if (!price) return '';
    if (typeof price !== 'number') return price;
    return new Intl.NumberFormat('vi-VN').format(price);
}

export async function formatAddress(provinceId, districtId, wardId, address) {
    try {
        const res = await Promise.all([
            apiService.provinces.getProvinces(provinceId),
            apiService.provinces.getDistricts(districtId),
            apiService.provinces.getWards(wardId)
        ]);

        const province = res[0].data.name
        const district = res[1].data.name
        const ward = res[2].data.name

        return `${address}, ${ward}, ${district}, ${province}`
    } catch (error) {
        console.error(error);

        return address || '';
    }
}

export function getImageUrl(path) {
    if (!path) return null;
    if (path.startsWith('http')) return path;
    return `${process.env.VUE_APP_API_BASE_URL}${path}`;
}