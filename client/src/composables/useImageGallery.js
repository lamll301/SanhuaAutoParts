/* how to use:
Dùng như bình thường thì chỉ cần khai báo như sau:
- listImagesClass là danh sách các sub img khi bấm sẽ hiện lên img chính
- mainImageClass là img chính muốn hiển thị
- clearMainImage là điều kiện có muốn xóa ảnh chính nếu click 2 lần vào 1 ảnh phụ hay không
- activeClass add hiệu ứng nếu ảnh được chọn
<img class=listImagesClass>    // ds img phụ
<img class=listImagesClass>
<img class=listImagesClass>
...
<img class=mainImageClass>      // img chính
Nếu như dùng ở nhiều nơi mà các đoạn code giống nhau thì cần khai báo thêm 2 class ở mỗi img chính và phụ là listImagesClass_index 
và mainImageClass_index, ví dụ:
// vị trí 1
<img class=listImagesClass, listImagesClass_index1>  // index tự truyền sao cho khác biệt với từng vị trí
<img class=listImagesClass, listImagesClass_index1>
<img class=listImagesClass, listImagesClass_index1>
...
<img class=mainImageClass, mainImageClass_index1> 
// vị trí 2
<img class=listImagesClass, listImagesClass_index2>
<img class=listImagesClass, listImagesClass_index2>
<img class=listImagesClass, listImagesClass_index2>
...
<img class=mainImageClass, mainImageClass_index2> 
// vị trí ...
*/
import { onMounted } from 'vue';

export default function useImageGallery() {
    
    onMounted(() => {
        setupImageClickHandlers('product-list-img-content', 'product-img', 'product-list-img-content--active');
        setupImageClickHandlers('product-comment-sub-img', 'product-comment-main-img', 'product-list-img-content--active', true);
    });

    const setupImageClickHandlers = (listImagesClass, mainImageClass, activeClass, clearMainImage = false) => {
        const listImages = document.querySelectorAll('.' + listImagesClass);
        listImages.forEach((image) => {
            image.addEventListener('click', function () {
                const clickedImageClasses = this.classList;
                const targetClass = Array.from(clickedImageClasses).find(cls => cls.startsWith(listImagesClass + '_'));
                if (targetClass) {
                    const clickedListImages = document.querySelectorAll('.' + targetClass);
                    // lấy ra số ở vị trí cuối cùng trong chuỗi
                    const lastDashIndex = targetClass.lastIndexOf('_');
                    const targetClassNumber = targetClass.substring(lastDashIndex + 1);
                    const mainImage = document.querySelector('.' + mainImageClass + '_' + targetClassNumber);
                    if (clearMainImage) {
                        const hasActiveClass = this.classList.contains(activeClass);
                        mainImage.src = hasActiveClass ? '' : this.src;
                        clickedListImages.forEach((image) => image.classList.remove(activeClass));
                        if (!hasActiveClass) this.classList.add(activeClass);
                    } else {
                        mainImage.src = this.src;
                        clickedListImages.forEach((image) => image.classList.remove(activeClass));
                        this.classList.add(activeClass);
                    }
                } else {    // undefined
                    let mainImage = document.querySelector('.' + mainImageClass);
                    if (clearMainImage) {
                        const hasActiveClass = this.classList.contains(activeClass);
                        mainImage.src = hasActiveClass ? '' : this.src;
                        listImages.forEach((image) => image.classList.remove(activeClass));
                        if (!hasActiveClass) this.classList.add(activeClass);
                    } else {
                        mainImage.src = this.src;
                        listImages.forEach((image) => image.classList.remove(activeClass));
                        this.classList.add(activeClass);
                    }
                }
            });
        });
    };

    return {
        setupImageClickHandlers,
    };
}
