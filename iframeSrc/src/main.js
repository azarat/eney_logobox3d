import Vue from 'vue';
import App from './App.vue';
import store from './store';
import vuetify from './plugins/vuetify';
import i18n from './i18n';

setTimeout(() => {
  // eslint-disable-next-line no-prototype-builtins
  if (!window.hasOwnProperty('printFrame')) {
    window.printFrame = {
      lang: 'ru',
      productModel: '51K001C00',
      sessionId: '54514',
      vizModelId: '20',
      siteId: '78789090',
      discount: '0',
    };
  }

  Vue.config.productionTip = false;

  new Vue({
    store,
    vuetify,
    i18n,
    render: h => h(App),
  }).$mount('#app');
}, 500);
