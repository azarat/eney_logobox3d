import { httpGet, httpPost } from '../../../helpers/_api';

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
      console.log(response);
      commit('setTypes', response.data);
    });
};

export const addPrint = ({ commit }) => {
  commit('pushPrint');
};

export const httpAllPrints = ({ state, rootState }) => {
  const { siteId, sessionId, productModel } = rootState.main;
  const payload = {
    prints: [],
    productModel,
    sessionId,
  };
  state.prints.forEach((print, index) => {
    payload.prints.push({
      comment: null,
      // fileUrl: '',
      id: index + 1,
      isFileLink: true,
      // remoteFileUrl: '',
      selectedApplicationType: print.type.id,
      selectedArea: print.area.id,
      selectedColor: print.colors,
      selectedCopy: 1,
    });
  });
  httpPost(`api/${siteId}/${sessionId}/prints`, payload).then((result) => {
    console.log(result);
  });
};

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
    .then((response) => {
      console.log(response);
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
