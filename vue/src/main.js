import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
import 'element-plus/theme-chalk/display.css'
import scui from './scui'
import i18n from './locales'
import router from './router'
import store from './store'
import { createApp } from 'vue'
import App from './App.vue'
import formCreate from '@/components/formCreate/index.js'

const app = createApp(App);

app.component('formCreate',formCreate);
app.use(store);
app.use(router);
app.use(ElementPlus, {size: 'small'});
app.use(i18n);
app.use(scui);





//挂载app
app.mount('#app');
