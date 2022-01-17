import ElementPlus from "element-plus";
import "element-plus/dist/index.css";
import "element-plus/theme-chalk/display.css";
import scui from "./scui";
import i18n from "./locales";
import router from "./router";
import store from "./store";
import { createApp } from "vue";
import App from "./App.vue";

import Codemirror from "codemirror-editor-vue3";
import "codemirror-editor-vue3/dist/style.css";
import formCreate from "@/components/formCreate";
const app = createApp(App);

app.component("formCreate", formCreate.$form());
app.use(store);
app.use(router);
app.use(ElementPlus, { size: "small" });
app.use(i18n);
app.use(scui);
app.use(Codemirror)
//挂载app
app.mount("#app");
