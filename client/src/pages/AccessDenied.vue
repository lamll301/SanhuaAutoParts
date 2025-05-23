<template>
    <div class="page-wrap">
        <div class="page-not-found">
        <img src="https://res.cloudinary.com/razeshzone/image/upload/v1588316204/house-key_yrqvxv.svg" class="img-key" alt="Access Key">
        <h1 class="text-xl">
            <span>4</span>
            <span>0</span>
            <span class="broken">3</span>
        </h1>
        <h4 class="text-sm text-sm-btm">
            Trang bạn đang cố gắng truy cập bị hạn chế quyền truy cập. Vui lòng kiểm tra lại tài khoản hoặc liên hệ quản trị viên để biết thêm chi tiết.<br>
            <a href="#" @click.prevent="goBack">Quay lại trang trước</a>
        </h4>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AccessDenied',
    methods: {
        goBack() {
            this.$router.go(-1);
        }
    }
}
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900|Nunito:200,300,400,600,700,800,900&display=swap");

.page-wrap {
    font-family: "Nunito", sans-serif;
    padding: 30px 15px;
    text-align: center;
    display: flex;
    align-items: center;
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.page-not-found {
    width: 400px;
    margin-left: auto;
    margin-right: auto;
    position: relative;
}

.img-key {
    margin-bottom: 0px;
    max-width: 100%;
    height: auto;
}

h1, h2, h3, h4, h5, h6 {
    font-family: "Montserrat", sans-serif;
}

h1.text-xl {
    color: #000;
    text-transform: uppercase;
    line-height: 50px;
    font-size: 160px;
    font-weight: 800;
    letter-spacing: -28px;
    text-shadow: -6px 4px 0px #fff;
    margin-left: -20px;
    margin-top: 50px;
    margin-bottom: 50px;
}

h1.text-xl span {
    transition: all 1s ease;
    display: inline-block;
    animation: pulse 5s infinite;
}

h4.text-md,
h4.text-sm {
    letter-spacing: 0.38px;
    font-weight: 300;
    line-height: 20px;
    font-size: 14px;
    text-transform: none;
    color: rgba(0, 0, 0, 0.5);
    margin-bottom: 0px;
    width: 100%;
}

h4.text-md,
h4.text-sm a {
    color: #1177bd;
    text-decoration: underline;
    font-weight: 700;
    cursor: pointer;
}

h4.text-md,
h4.text-sm a:hover,
h4.text-md,
h4.text-sm a:focus {
    text-decoration: none;
}

h4.text-md {
    font-size: 50px;
    font-weight: 700;
    color: #1177bd;
    text-transform: none;
}

h4.text-sm-btm {
    top: auto;
    bottom: 40px;
}

@keyframes pulse {
    0% {
        color: #000;
    }
    50% {
        color: rgba(240, 48, 48, 1);
    }
    100% {
        color: #000;
    }
}

@media (max-width: 768px) {
    .page-wrap {
        padding: 20px 15px;
    }
  
    .page-not-found {
        width: 100%;
        max-width: 350px;
    }
  
    h1.text-xl {
        font-size: 120px;
        letter-spacing: -20px;
        margin-bottom: 30px;
    }
  
    h4.text-sm {
        top: 10px;
        font-size: 12px;
        padding: 0 10px;
    }
  
    h4.text-sm-btm {
        bottom: -60px;
    }
  
    h4.text-md {
        font-size: 30px;
    }
}
</style> 