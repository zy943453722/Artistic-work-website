import Vue from 'vue';
import App from './App.vue';
import ElementUI from 'element-ui';
import MuseUI from 'muse-ui';
import 'muse-ui/dist/muse-ui.css';
import 'element-ui/lib/theme-chalk/index.css';
import router from './router';
import theme from 'muse-ui/lib/theme';

Vue.config.productionTip = false;
Vue.use(ElementUI);
Vue.use(MuseUI);
theme.use('light');

new Vue({
  render: h => h(App),
  router
}).$mount('#app');
