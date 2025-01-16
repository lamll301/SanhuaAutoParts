<!-- RatingStars.vue -->
<template>
    <div class="star-rating-container">
        <div class="filled-stars" :style="{ width: `${percentage}%` }">
            <i :class="starClass" class="fa-solid fa-star" v-for="n in 5" :key="n"></i>
        </div>
        <div class="empty-stars">
            <i :class="starClass" class="fa-solid fa-star" v-for="n in 5" :key="n"></i>
        </div>
    </div>
</template>
  
<script>
export default {
    props: {
        rating: {
            type: Number,
            required: true,
        },
        starClass: {
            type: String,
            default: ""
        }
    },
    computed: {
        percentage() {
            return (this.rating / 5) * 100;
        },
    },
};
</script>
  
<style scoped>
.star-rating-container {
    position: relative;
}
.empty-stars {
    color: #e0e0e0;
}
.filled-stars {
    position: absolute;
    top: 0;
    left: 0;
    white-space: nowrap;
    overflow: hidden;
    color: #fdb819;
}
</style>
  