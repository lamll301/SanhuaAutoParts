<template>
    <div class="read-more__content" ref="content">
        <slot></slot>
    </div>
    <button class="read-more" ref="button" @click="toggleReadMore">{{ buttonText }}</button>
</template>

<script>
export default {
    data() {
        return {
            isExpanded: false,
            buttonText: 'Xem thêm',
        };
    },
    mounted() {
        this.setupReadMoreBtn();
    },
    methods: {
        setupReadMoreBtn() {
            const content = this.$refs.content;
            const isOverflowing = content.scrollHeight > content.offsetHeight;
            if (isOverflowing) {
                this.$refs.button.style.display = 'inline-block';
            }
        },
        toggleReadMore() {
          this.isExpanded = !this.isExpanded;
          this.buttonText = this.isExpanded ? 'Thu gọn' : 'Xem thêm';
          this.$refs.content.classList.toggle('expanded', this.isExpanded);
        },
    }
}
</script>

<style>
.read-more {
    display: none;
    color: blue;
    background: none;
    border: none;
    padding: 0;
    text-decoration: underline;
}
.read-more__content {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    position: relative;
}
.read-more__content.expanded {
    -webkit-line-clamp: unset;
    overflow: visible;
}
</style>