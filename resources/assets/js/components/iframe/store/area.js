import Vue from 'vue';
import HTTP from '../http.js';

export default {
  namespaced: true,
  state: {
    areas: [],
  },
  actions: {
    fetchAreas({ commit, state, rootState }, params) {
      return HTTP().get(
          params.lang
          + '/areas/by-product/'
          + params.productModel
        )
        .then(({ data  }) => {
          commit('setAreas', data);
        });
    },
  },
  getters: {

  },
  mutations: {
    setAreas(state, areas) {
      state.areas = areas;
    },
  },
};
