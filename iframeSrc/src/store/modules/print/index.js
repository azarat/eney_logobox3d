import * as getters from './getters';
import * as mutations from './mutations';
import * as actions from './actions';

export default {
  namespaced: true,
  state: {
    colors: [],
    types: [],
    areas: [],

    quantityOfItems: 100,
    prints: [],

    additionalFilesType: 'file',
    linkUrl: '',
    fileData: [],

    printsDataId: '',
  },
  mutations,
  getters,
  actions,
};
