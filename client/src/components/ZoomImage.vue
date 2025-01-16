<template>
    <div class="zoom-container" :class="containerClass" @click="zoomImageClicked">
        <img class="zoom-image" :id="imageId" :class="imageClass" :src="imageSrc" alt="">
    </div>
</template>

<script>
export default {
    props: {
        imageSrc: {
            type: String,
            required: true
        },
        imageClass: {
            type: String,
            default: ''
        },
        containerClass: {
            type: String,
            default: ''
        },
        imageId: {
            type: String,
            default: ''
        }
    },
    methods: {
        zoomImageClicked() {
            this.$el.classList.toggle('zoomed');
            this.handleZoomImage();
        },
        handleZoomImage() {
            const zoomImage = this.$el.querySelector('.zoom-image');
            this.$el.addEventListener('mousemove', (e) => {
                const rect = this.$el.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const xPercent = (x / rect.width) * 100;
                const yPercent = (y / rect.height) * 100;
                zoomImage.style.transformOrigin = `${xPercent}% ${yPercent}%`;
            });
        },
    }
}
</script>

<style scoped>
.zoom-container {
    position: relative;
    overflow: hidden;
    cursor: zoom-in;
}
.zoom-container.zoomed {
    cursor: zoom-out;
}
.zoom-container img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    transition: transform 0.5s ease;
}
.zoomed img {
    transform: scale(3);
}
</style>