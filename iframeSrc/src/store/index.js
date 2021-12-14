import Vue from 'vue';
import Vuex from 'vuex';

import main from './modules/main';
import print from './modules/print';

Vue.use(Vuex);

const store = new Vuex.Store({
  modules: {
    main,
    print,
  },
});

export default store;
