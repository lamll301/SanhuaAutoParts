<template>
  <div class="not-found-container">
    <div class="content-wrapper">
      <vue3-lottie :animationData="animationData" :height="400" :width="400" />
      <h1 class="title">
        OPPS! - KHÔNG TÌM THẤY TRANG
      </h1>
      <p class="subtitle">Ôi! Có vẻ như trang bạn đang tìm kiếm đã biến mất vào thinh không...</p>
    </div>
    
    <div class="decoration">
      <div class="circle circle-1"></div>
      <div class="circle circle-2"></div>
    </div>
  </div>
</template>

<script>
import { Vue3Lottie } from 'vue3-lottie';
import animationData from '@/assets/images/Animation - 1743054722358.json';

export default {
    name: 'NotFound',
    components: { Vue3Lottie },
    setup() {
      return { animationData };
    },
}
</script>

<style scoped>
.not-found-container {
  position: relative;
  width: 100%;
  height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  overflow: hidden;
}

.content-wrapper {
  text-align: center;
  z-index: 2;
}

.title {
  font-size: 2.5rem;
  color: #2c3e50;
  margin-top: -20px;
  margin-bottom: 15px;
  font-weight: 600;
}

.subtitle {
  font-size: 1.4rem;
  color: #7f8c8d;
  max-width: 500px;
  margin: 0 auto;
  line-height: 1.6;
}

.decoration {
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  z-index: 1;
}

.circle {
  position: absolute;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.2);
}

.circle-1 {
  width: 300px;
  height: 300px;
  top: 10%;
  left: 10%;
}

.circle-2 {
  width: 200px;
  height: 200px;
  bottom: 15%;
  right: 15%;
}

@media (max-width: 768px) {
  .title {
    font-size: 2rem;
  }
  
  .subtitle {
    font-size: 1rem;
    padding: 0 20px;
  }
}
</style>