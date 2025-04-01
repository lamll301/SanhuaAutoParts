
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const phoneRegex = /^[0-9]{9,15}$/;
const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;

export function isValidEmail(email) {
    return emailRegex.test(email.trim());
}

export function isValidPhone(phone) {
    return phoneRegex.test(phone.trim());
}

export function isValidPassword(password) {
    return passwordRegex.test(password.trim());
}
