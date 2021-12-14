/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window._ = require('lodash');

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

window.Vue = require('vue');

var Lang = require('vuejs-localization');
Lang.requireAll(require.context('./components/iframe/lang', true, /\.js$/));

Vue.use(Lang);

Vue.component('print-form', require('./components/iframe/PrintForm.vue'));
Vue.component('custom-select', require('./components/iframe/CustomSelect.vue'));
Vue.component('custom-radio', require('./components/iframe/CustomRadio.vue'));
Vue.component('svg-icon', require('./components/SvgIcon'));

import store from './components/iframe/store/index.js';


const app = new Vue({
  store
}).$mount('#app');
