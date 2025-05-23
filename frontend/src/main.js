import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import '../src/style.css';
import VueTheMask from 'vue-the-mask';

const app = createApp(App);
app.use(router);
app.mount('#app');
app.use(VueTheMask);
