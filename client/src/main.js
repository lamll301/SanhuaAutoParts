import { createApp } from 'vue';
import App from './App.vue';
import router from './router/index.js';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import axios from "axios";
import VueAxios from 'vue-axios';
import sweetalert from './plugins/sweetalert';
import { createPinia } from 'pinia';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';

const app = createApp(App);
const pinia = createPinia()
pinia.use(piniaPluginPersistedstate)
app.use(router);
app.use(VueSweetalert2);
app.use(VueAxios, { $request: axios });
app.use(sweetalert)
app.use(pinia)
app.mount('#app');
