import { format, parseISO } from 'date-fns';

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

export function getImageUrl(path) {
    if (!path) return null;
    if (path.startsWith('http')) return path;
    return `${process.env.VUE_APP_API_BASE_URL}${path}`;
}