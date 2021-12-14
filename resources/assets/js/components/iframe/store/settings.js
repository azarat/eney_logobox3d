import Vue from 'vue';
import HTTP from '../http.js';

export default {
  namespaced: true,
  state: {
    lang: null,
    sessionId: null,
    productModel: null,
    siteId: null,
    colors: {},
    mainButtonIsHover: false,
    isButtonActive: false,
  },
  actions: {
    fetchColors({commit, state}) {
      return HTTP().get(state.siteId + '/settings/colors')
        .then((data) => {
          commit('setColors', data.data);
        });
    }
  },
  getters: {
    getMainButtonStyle: function (state) {
      if (_.isEmpty(state.colors)) {
        return {};
      }

      let style = {};
      if (state.mainButtonIsHover) {
        style.backgroundColor = state.colors.main_button_color_hover;
      } else {
        if (state.isButtonActive) {
          style.backgroundColor = state.colors.main_button_color_active;
        } else {
          style.backgroundColor = state.colors.main_button_color;
        }
      }

      return style;
    }

  },
  mutations: {
    setLang(state, lang) {
      state.lang = lang;
    },
    setSessionId(state, sessionId) {
      state.sessionId = sessionId;
    },
    setProductModel(state, productModel) {
      state.productModel = productModel;
    },
    setSiteId(state, siteId) {
      state.siteId = siteId;
    },
    setColors(state, colors) {
      state.colors = colors;
    },
    setMainButtonHovered(state) {
      state.mainButtonIsHover = true;
    },
    setMainButtonNonHovered(state) {
      state.mainButtonIsHover = false;
    },
    setMainButtonActive(state) {
      state.isButtonActive = true;
    }
  },
};
