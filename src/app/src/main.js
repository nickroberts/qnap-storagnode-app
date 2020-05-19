import Vue from 'vue';
import Vuex from 'vuex';
import App from './App.vue';
import router from './router';
import vuetify from './plugins/vuetify';

import { config } from './state/config/index';
import { status } from './state/status/index';

Vue.config.productionTip = false;
Vue.use(Vuex);

const store = new Vuex.Store({
  modules: {
    config,
    status
  }
});

new Vue({
  router,
  store,
  vuetify,
  render: h => h(App)
}).$mount('#app');
