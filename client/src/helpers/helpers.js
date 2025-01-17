
export function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price);
}

export function totalPrice(array) {
    let ans = 0;
    array.forEach((item) => {
        ans += item.price * item.quantity;
    });
    return ans;
}

export function getImageUrl(imageName) {
    try {
        return require(`@/assets/images/data/${imageName}`);
    } 
    catch {
        return require('@/assets/images/error.jpg');
    }
}