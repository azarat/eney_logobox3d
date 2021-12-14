import * as getters from './main_getters';
import * as mutations from './main_mutations';
import * as actions from './main_actions';

export default {
  namespaced: true,
  state: {
    overlay: false,
    dialog: false,

    lang: null,
    sessionId: null,
    productModel: null,
    siteId: null,
    vizModelId: null,
    discount: '0',

    // siteId: '', // for testing
    // product: '51K001C00', // for testing
    isEvaluatorActive: false,
    isModelActive: false,
    isAdditionActive: false,
  },
  mutations,
  getters,
  actions,
};
