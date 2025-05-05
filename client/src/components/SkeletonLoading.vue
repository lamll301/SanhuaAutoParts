<template>
    <div class="skeleton-wrapper" :class="{ 'skeleton-animate': animate }">
        <div v-for="(item, index) in Array(count)" 
            :key="index" 
            class="skeleton-item"
            :style="{
                width: width,
                height: height,
                borderRadius: borderRadius,
                marginBottom: marginBottom
            }"
        ></div>
    </div>
</template>
  
<script>
export default {
    name: 'SkeletonLoading',
    props: {
        count: {
            type: Number,
            default: 1
        },
        height: {
            type: String,
            default: '20px'
        },
        width: {
            type: String,
            default: '100%'
        },
        borderRadius: {
            type: String,
            default: '4px'
        },
        marginBottom: {
            type: String,
            default: '10px'
        },
        animate: {
            type: Boolean,
            default: true
        }
    }
}
</script>
  
<style scoped>
.skeleton-wrapper {
    width: 100%;
}
  
.skeleton-item {
    background-color: #e0e0e0;
    overflow: hidden;
    position: relative;
}
  
.skeleton-animate .skeleton-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
    animation: skeleton-loading 1.5s infinite;
}
  
@keyframes skeleton-loading {
    0% {
      transform: translateX(-100%);
    }
    100% {
      transform: translateX(100%);
    }
}
</style>