import * as getters from './getters';
import * as mutations from './mutations';
import * as actions from './actions';

export default {
  namespaced: true,
  state: {
    colors: [],
    types: [],
    areas: [],
    typesTech: [],

    quantityOfItems: 100,
    prints: [],
    printsCost: [0],
    printsCostDiscount: [0],

    additionalFilesType: 'link',
    productData: null,
    linkUrl: '',
    fileData: [],

    printsDataId: '',
  },
  mutations,
  getters,
  actions,
};
