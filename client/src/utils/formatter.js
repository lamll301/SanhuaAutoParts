
import { format, parseISO } from 'date-fns';

export function formatAddress(address, wardId, districtId, cityId, locations = {}) {
    const { cities = [], districts = [], wards = [] } = locations;
    const addressParts = [];
    
    if (address) addressParts.push(address);
    
    if (wardId) {
        const ward = wards.find(w => w.code === wardId);
        if (ward) addressParts.push(ward.name);
    }
    
    if (districtId) {
        const district = districts.find(d => d.code === districtId);
        if (district) addressParts.push(district.name);
    }
    
    if (cityId) {
        const city = cities.find(c => c.code === cityId);
        if (city) addressParts.push(city.name);
    }
    
    return addressParts.join(', ');
}

export function formatDate(date, pattern = 'yyyy-MM-dd HH:mm:ss') {
    if (!date) return;
    try {
        return format(parseISO(date), pattern);
    } catch (error) {
        console.error('Lỗi định dạng ngày tháng:', error);
        return date;
    }
}

export function formatPrice(price) {
    if (!price) return '';
    if (typeof price !== 'number') return price;
    return new Intl.NumberFormat('vi-VN').format(price);
}