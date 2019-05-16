import Vue from 'vue';
import App from './App.vue';
import ElementUI from 'element-ui';
import MuseUI from 'muse-ui';
import 'muse-ui/dist/muse-ui.css';
import 'element-ui/lib/theme-chalk/index.css';
import router from './router';
import theme from 'muse-ui/lib/theme';
import store from '../store/index.js';
import './assets/iconfont/iconfont.css';
import UUID from 'vue-uuid';
import Share from 'vue-social-share';
import 'vue-social-share/dist/client.css';

Vue.config.productionTip = false;
Vue.use(ElementUI);
Vue.use(MuseUI);
Vue.use(UUID);
Vue.use(Share);

new Vue({
  render: h => h(App),
  router,
  theme,
  store,
}).$mount('#app');
