export const setter = (state, payload) => {
  if (typeof payload.value === 'object' && payload.value !== null) {
    state[payload.prop] = Object.assign({}, state[payload.prop], payload.value);
  } else {
    state[payload.prop] = payload.value;
  }
};

export const activateEvaluator = (state) => {
  state.isEvaluatorActive = true;
};

export const deactivateEvaluator = (state) => {
  state.isEvaluatorActive = false;
  state.isModelActive = false;
  state.isAdditionActive = false;
};

export const activateModel = (state) => {
  if (state.isEvaluatorActive) {
    state.isModelActive = true;
  }
};

export const deactivateModel = (state) => {
  state.isModelActive = false;
};

export const activateAddition = (state) => {
  if (state.isEvaluatorActive) {
    state.isAdditionActive = true;
  }
};

export const deactivateAddition = (state) => {
  state.isAdditionActive = false;
};

export const setLang = (state, lang) => {
  state.lang = lang;
};

export const setSessionId = (state, sessionId) => {
  state.sessionId = sessionId;
};

export const setProductModel = (state, productModel) => {
  state.productModel = productModel;
};

export const setSiteId = (state, siteId) => {
  state.siteId = siteId;
};

export const setVizModelId = (state, vizModelId) => {
  state.vizModelId = vizModelId;
};
