export const setter = (state, payload) => {
  if (typeof payload.value === 'object' && payload.value !== null) {
    state[payload.prop] = Object.assign({}, state[payload.prop], payload.value);
  } else {
    state[payload.prop] = payload.value;
  }
};

export const setColors = (state, colors) => {
  state.colors = colors;
};

export const setTypes = (state, types) => {
  state.types = types;
};

export const setProductData = (state, productData) => {
  state.productData = productData;
};

export const setPrintsCost = (state, printsCost) => {
  state.printsCost = printsCost;
};

export const setPrintsCostDiscount = (state, printsCostDiscount) => {
  state.printsCostDiscount = printsCostDiscount;
};

export const setAreas = (state, areas) => {
  state.areas = areas;
};

export const setTypesTech = (state, types) => {
  console.log(types);
  state.typesTech = types;
};

export const clearPrints = (state) => {
  state.prints = [];
};

export const pushPrint = (state) => {
  const print = {
    colors: 1,
    type: '1',
    area: '1',
    typesTech: '1',
  };
  state.prints.push(print);
  // window.console.log(print);
  // window.console.log(state.prints);
};

export const setTypeOfPrint = (state, payload) => {
  state.prints[payload.index].type = payload.value;
};

export const setAreaOfPrint = (state, payload) => {
  state.prints[payload.index].area = payload.value;
};

export const setTypeTechOfPrint = (state, payload) => {
  state.prints[payload.index].typesTech = payload.value;
};

export const setColorsOfPrint = (state, payload) => {
  state.prints[payload.index].colors = payload.value;
};


export const setAdditionalFilesType = (state, payload) => {
  state.additionalFilesType = payload;
};

export const setLinkUrl = (state, payload) => {
  state.linkUrl = payload;
};

export const setFileData = (state, payload) => {
  state.fileData = payload;
};
