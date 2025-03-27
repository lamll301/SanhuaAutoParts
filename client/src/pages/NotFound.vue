<template>
  <div class="not-found-container">
    <div class="content-wrapper">
      <h1>Oops! Lạc đường rồi</h1>
      <p class="description">Liên kết này có thể đã bị xóa hoặc không tồn tại.</p>
      
      <div class="animation-wrapper">
        <vue3-lottie
          :animationData="animationData"
          :height="350"
          :width="350"
          :speed="1.5"
        />
      </div>
      
      <button @click="goBack" class="button-55" role="button">
        Quay lại trang trước
      </button>
    </div>
  </div>
</template>

<script>
import { defineComponent } from 'vue';
import { Vue3Lottie } from 'vue3-lottie';
import animationData from '@/assets/images/Animation - 1743054722358.json';
import { useRouter } from 'vue-router';

export default defineComponent({
  name: 'NotFound',
  components: {
    Vue3Lottie,
  },
  setup() {
    const router = useRouter();
    
    const goBack = () => {
      router.go(-1);
    };

    return {
      animationData,
      goBack
    };
  },
});
</script>

<style scoped>
.not-found-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  text-align: center;
  padding: 20px;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.content-wrapper {
  max-width: 600px;
  width: 100%;
}

h1 {
  font-size: 2.5rem;
  color: #2c3e50;
  margin-bottom: 1rem;
  font-weight: 700;
}

.description {
  font-size: 1.2rem;
  color: #7f8c8d;
  margin-bottom: 2.5rem;
  line-height: 1.6;
}

.animation-wrapper {
  margin: 0 auto 2.5rem;
  filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.1));
}

@media (max-width: 768px) {
  h1 {
    font-size: 2rem;
  }
  .description {
    font-size: 1rem;
  }
  .animation-wrapper {
    margin-bottom: 2rem;
  }
}

/* Button 55 */
.button-55 {
  align-self: center;
  background-color: #fff;
  background-image: none;
  background-position: 0 90%;
  background-repeat: repeat no-repeat;
  background-size: 4px 3px;
  border-radius: 15px 225px 255px 15px 15px 255px 225px 15px;
  border-style: solid;
  border-width: 2px;
  box-shadow: rgba(0, 0, 0, .2) 15px 28px 25px -18px;
  box-sizing: border-box;
  color: #41403e;
  cursor: pointer;
  display: inline-block;
  font-family: Neucha, sans-serif;
  font-size: 1.6rem;
  line-height: 23px;
  outline: none;
  padding: .75rem;
  text-decoration: none;
  transition: all 235ms ease-in-out;
  border-bottom-left-radius: 15px 255px;
  border-bottom-right-radius: 225px 15px;
  border-top-left-radius: 255px 15px;
  border-top-right-radius: 15px 225px;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
}

.button-55:hover {
  box-shadow: rgba(0, 0, 0, .3) 2px 8px 8px -5px;
  transform: translate3d(0, 2px, 0);
}

.button-55:focus {
  box-shadow: rgba(0, 0, 0, .3) 2px 8px 4px -6px;
}

</style>