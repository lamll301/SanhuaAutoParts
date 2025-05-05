import { format, parseISO } from 'date-fns';

const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const phoneRegex = /^[0-9]{9,15}$/;
const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;

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

export function isValidEmail(email) {
    return emailRegex.test(email.trim());
}

export function isValidPhone(phone) {
    return phoneRegex.test(phone.trim());
}

export function isValidPassword(password) {
    return passwordRegex.test(password.trim());
}

export function getImageUrl(path) {
    if (!path) return null;
    if (path.startsWith('http')) return path;
    return `${process.env.VUE_APP_API_BASE_URL}${path}`;
}