import Vue from 'vue';
import HTTP from '../http.js';

export default {
  namespaced: true,
  state: {
    applicationTypes: [],
  },
  actions: {
    fetchApplicationTypes({ commit }, param) {
      return HTTP().get(
          param.lang
          + '/application-types/by-product/'
          + param.productModel
        )
        .then(({ data }) => {
          commit('setApplicationTypes', data);
        });
    },
  },
  getters: {

  },
  mutations: {
    setApplicationTypes(state, applicationTypes) {
      state.applicationTypes = applicationTypes;
    },
  },
};
