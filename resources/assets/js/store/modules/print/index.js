import * as getters from './getters';
import * as mutations from './mutations';
import * as actions from './actions';

export default {
  namespaced: true,
  state: {
    colors: [],
    types: [],
    areas: [],

    prints: [],

    additionalFilesType: 'file',
    linkUrl: '',
    fileData: [],
  },
  mutations,
  getters,
  actions,
};
