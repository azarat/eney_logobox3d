import { httpDelete, httpGet, httpPost } from '../../../helpers/_api';

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
export const destroyPrint = ({ rootState }, id) => {
  // eslint-disable-next-line radix
  const printId = parseInt(id);
  const { siteId, sessionId } = rootState.main;
  httpDelete(`api/${siteId}/${sessionId}/prints/${printId}`, {})
    .then(() => {});
};

export const addPrint = ({ commit }) => {
  commit('pushPrint');
};

export const httpAllPrints = ({ commit, state, rootState }) => {
  const { siteId, sessionId, productModel } = rootState.main;
  const payload = {
    prints: [],
    productModel,
    sessionId,
  };
  state.prints.forEach((print, index) => {
    // item.selectedApplicationType = (print.type && print.type.id) ? print.type.id : 1;
    let areaId = 1;
    let appTypeId = 1;
    // eslint-disable-next-line no-prototype-builtins
    if (print.hasOwnProperty('area') && print.area.hasOwnProperty('id')) {
      areaId = print.area.id;
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
      selectedColor: print.colors,
      selectedCopy: 1,
    });
  });
  httpPost(`api/${siteId}/${sessionId}/prints`, payload).then(() => {
    commit('main/setter', {
      prop: 'overlay',
      value: false,
    }, { root: true });
    commit('main/setter', {
      prop: 'dialog',
      value: true,
    }, { root: true });
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
  dispatch('httpAllPrints');
  httpPost(`api/${siteId}/${sessionId}/${productModel}/printsdata`, payload)
    .then(() => {
      // console.log(response);
    }).catch(() => {});
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
