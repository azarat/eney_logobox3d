import {
  httpGet, httpPost, httpGetProduct,
} from '../../../helpers/_api';

export const loadProductData = ({ commit, rootState }) => {
  const { productModel } = rootState.main;

  const urlParams = new URLSearchParams(window.location.search);
  const productSku = (urlParams.get('product_sku')) ? urlParams.get('product_sku') : productModel;

  httpGetProduct('index.php?route=product/product/getProductData',
    { productSku }, {}, 'http://dev.eney.com.ua:8081/', true)
    .then((response) => {
      commit('setProductData', response.data);
    })
    .catch((e) => {
      console.log('CORS Error');
      console.log(e);
    });
};
export const loadTypesTech = ({ commit, rootState }) => {
  const { lang, productModel } = rootState.main;
  httpGet(`api/${lang}/types/by-product/${productModel}`)
    .then((response) => {
      commit('setTypesTech', response.data);
    });
};
export const loadAreas = ({ commit, rootState }) => {
  const { lang, productModel } = rootState.main;
  httpGet(`api/${lang}/areas/by-product/${productModel}`)
    .then((response) => {
      commit('setAreas', response.data);
    });
};
export const loadTypes = ({ commit, rootState }) => {
  const { lang, productModel } = rootState.main;
  httpGet(`api/${lang}/application-types/by-product/${productModel}`)
    .then((response) => {
      // console.log(response);
      commit('setTypes', response.data);
    });
};
export const loadPrints = ({ rootState }) => new Promise((resolve) => {
  const {
    siteId,
    sessionId,
    productModel,
    lang,
  } = rootState.main;
  httpGet(`api/${lang}/${siteId}/${sessionId}/prints/${productModel}`)
    .then((response) => {
      resolve(response);
      // commit('setTypes', response.data);
    });
});
export const destroyPrint = () => {};
// export const destroyPrint = ({ rootState }, id) => {
//   // eslint-disable-next-line radix
//   const printId = parseInt(id);
//   const { siteId, sessionId } = rootState.main;
//   httpDelete(`api/${siteId}/${sessionId}/prints/${printId}`, {})
//     .then(() => {});
// };

export const addPrint = ({ commit }) => {
  commit('pushPrint');
};

export const goBack = () => {
  // document.location.href = document.location.origin + document.location.pathname;
  window.history.back();
};

export const httpAllPrints = ({ commit, state, rootState }) => {
  const { siteId, sessionId, productModel } = rootState.main;
  const payload = {
    prints: [],
    productModel,
    sessionId,
  };

  state.prints.forEach((print, index) => {
    console.log('print');
    console.log(print);
    // item.selectedApplicationType = (print.type && print.type.id) ? print.type.id : 1;
    let areaId = 1;
    let typeId = 1;
    let appTypeId = 1;
    // eslint-disable-next-line no-prototype-builtins
    if (print.hasOwnProperty('area') && print.area.hasOwnProperty('id')) {
      areaId = print.area.id;
    }
    // eslint-disable-next-line no-prototype-builtins
    if (print.hasOwnProperty('typeTech') && print.typesTech.hasOwnProperty('id')) {
      typeId = print.typesTech.id;
    }
    // eslint-disable-next-line no-prototype-builtins
    if (print.hasOwnProperty('type') && print.type.hasOwnProperty('id')) {
      appTypeId = print.type.id;
    }
    payload.prints.push({
      comment: null,
      // fileUrl: '',
      id: index + 1,
      isFileLink: true,
      // remoteFileUrl: '',
      selectedApplicationType: appTypeId,
      selectedArea: areaId,
      selectedType: typeId,
      selectedColor: print.colors,
      selectedCopy: 1,
    });
  });

  httpPost(`api/${siteId}/${sessionId}/prints`, payload).then((res) => {
    console.log('payload');
    console.log(payload);

    console.log('res');
    console.log(res.data);

    commit('main/setter', {
      prop: 'overlay',
      value: false,
    }, { root: true });

    commit('main/setter', {
      prop: 'dialog',
      value: true,
    }, { root: true });
  }).catch((e) => {
    console.error(e);
  });
};

export const getPrintDataId = async ({ rootState }) => new Promise(async (resolve) => {
  // eslint-disable-next-line no-use-before-define
  const printDataId = await preparePrintDataId(rootState);
  resolve(printDataId.toString());
});

export const submitFullForm = async ({ rootState, state, dispatch }) => {
  const { siteId, sessionId, productModel } = rootState.main;

  // eslint-disable-next-line no-use-before-define
  const printDataId = await preparePrintDataId(rootState);

  const payload = {
    comment: '',
    fileUrl: '',
    isFileLink: state.additionalFilesType,
    printsDataId: printDataId,
    remoteFileUrl: state.linkUrl,
  };

  if (state.additionalFilesType === 'file') {
    const formData = new FormData();
    formData.append('file', state.fileData);
    httpPost('api/upload-file', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }).then((response) => {
      payload.fileUrl = response.data;
    }).catch(() => {});
  }

  console.log(payload);

  dispatch('httpAllPrints');
  httpPost(`api/${siteId}/${sessionId}/${productModel}/printsdata`, payload)
    .then((response) => {
      console.log('response');
      console.log(response);
    }).catch(e => console.log(e));
};

function preparePrintDataId(rootState) {
  return new Promise((resolve, reject) => {
    const { siteId, sessionId, productModel } = rootState.main;
    const payload = {
      comment: '',
      fileUrl: '',
      isFileLink: false,
      printsDataId: null,
      remoteFileUrl: '',
    };
    httpPost(`api/${siteId}/${sessionId}/${productModel}/printsdata`, payload)
      .then((response) => {
        resolve(response.data);
      }).catch((err) => {
        reject(err);
      });
  });
}
