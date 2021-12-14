import Vue from 'vue';
import Vuex from 'vuex';
import area from './area';
import settings from './settings';
import applicationTypes from './applicationTypes';
import prints from './prints';
import printsData from './printsData';

Vue.use(Vuex);

export default new Vuex.Store({
  strict: true,
  state: {
    baseUrl: '/api',
  },
  modules: {
    area,
    settings,
    applicationTypes,
    prints,
    printsData,
  },
  mutations: {

  },
  actions: {

  },
  plugins: [
    // createPersistedState(),
  ],
});
