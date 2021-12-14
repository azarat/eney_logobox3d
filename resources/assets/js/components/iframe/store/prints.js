import Vue from 'vue';
import HTTP from '../http.js';
import helpers from '../helpers.js';

export default {
  namespaced: true,
  state: {
    prints: [],
    totalPushPrints: [],
  },
  actions: {
    fetchDuringPrints({ commit, state, rootState, dispatch }, params) {
      return HTTP().get(
        rootState.settings.siteId
        + '/' + rootState.settings.sessionId
        + '/prints/during/'
        + rootState.settings.productModel
        ).then((data) => {
          if (data.data.length != 0) {
              _.forEach(data.data, async (item) => {
                  const printId = _.uniqueId();
                  let print = {
                      id: printId,
                      storedId: item.id || null,
                      applicationTypes: rootState.applicationTypes.applicationTypes,
                      areas: [],
                      colors: [],
                      selectedColor: item.colors_qty || null,
                      copies: [],
                      selectedCopy: item.copies_qty || null,
                      comment: item.comment || null,
                      isClose: false,
                      isFileLink: item.is_file_link == '0' ? false : true,
                      fileUrl: item.file_url,
                      remoteFileUrl: item.remote_file_url,
                  };
                  print.selectedApplicationType = await helpers.getApplicationType(
                      rootState.applicationTypes.applicationTypes,
                      item.application_type_id || null
                  );
                  print.selectedArea = await helpers.getArea(
                      rootState.area.areas,
                      item.area_id || null
                  );
                  commit('pushPrintToPrints', print);
                  commit('setAreasForPrint', {
                      printId: printId,
                      areas: helpers.filterAreasByApplicationType (
                          rootState.area.areas,
                          helpers.getPrintSelectedApplicationType(state.prints, printId)
                      )
                  });
                  commit('setColorsForPrint', {
                      printId: printId,
                      colors: helpers.getPrintColors(state.prints, printId)
                  });
                  commit('setCopiesForPrint', {
                      printId: printId,
                      copies: helpers.getPrintCopies(state.prints, printId)
                  });
              });
          } else {
            dispatch('addPrint');
          }
        });
    },
      /**
       * Push prints to the server
       *
       * @param commit
       * @param state
       * @param rootState
       * @param params {productModel: X, sessionId: X}
       * @returns {Promise<T | never>}
       */
    pushPrints({ commit, state, rootState }, params) {
      let printsForStore = _.reduce(state.prints, function (carry, item) {
        let newItem = [];
        newItem = _.pick(item, [
          'id',
          'selectedApplicationType',
          'selectedArea',
          'selectedColor',
          'selectedCopy',
          'storedId',
          'comment',
          'fileUrl',
          'remoteFileUrl',
          'isFileLink',
        ]);
        newItem.selectedApplicationType = item.selectedApplicationType.id;
        newItem.selectedArea = item.selectedArea.id;
        carry.push(newItem);
        return carry;
      }, []);
      // return HTTP().post(
      //   rootState.settings.siteId
      //   + '/' + rootState.settings.sessionId
      //   + '/prints'
      //   , {
      //     prints: printsForStore,
      //     productModel: params.productModel,
      //     sessionId: params.sessionId,
      //   }).then(({ data }) => {
      //   let printsForSave = _.reduce(state.prints, function (carry, item) {
      //     let newItem = item;
      //     newItem.storedId = data[item.id];
      //     carry.push(newItem);
      //     return carry;
      //   }, []);
      //   commit('setPrints', printsForSave);
      // });
      state.totalPushPrints.push({
        prints: printsForStore,
        productModel: params.productModel,
        sessionId: params.sessionId,
      });
    },
    httpAllPrints({state, getters, rootState, commit, dispatch}) {
      state.totalPushPrints.forEach((itemPrint) => {
        HTTP().post(
          rootState.settings.siteId
          + '/' + rootState.settings.sessionId
          + '/prints'
          , {
            prints: itemPrint.prints,
            productModel: itemPrint.productModel,
            sessionId: itemPrint.sessionId,
          }).then(({ data }) => {
          // let printsForSave = _.reduce(state.prints, function (carry, item) {
          //   let newItem = item;
          //   newItem.storedId = data[item.id];
          //   carry.push(newItem);
          //   return carry;
          // }, []);
          // commit('setPrints', printsForSave);
          });
      });
    },
    addPrint ({state, getters, rootState, commit, dispatch}) {
      const printId = _.uniqueId();
      let print = {
        id: printId,
        applicationTypes: rootState.applicationTypes.applicationTypes,
        selectedApplicationType: null,
        areas: [],
        selectedArea: null,
        colors: [],
        selectedColor: null,
        copies: [],
        selectedCopy: null,
        comment: null,
        isFileLink: false,
        fileUrl: '',
        remoteFileUrl: '',
      };
      commit('pushPrintToPrints', print);
      commit('setSelectedApplicationTypeForPrint', {
        printId: printId,
        applicationType: _.head(rootState.applicationTypes.applicationTypes)
      });
      commit('setAreasForPrint', {
        printId: printId,
        areas: helpers.filterAreasByApplicationType (
          rootState.area.areas,
          helpers.getPrintSelectedApplicationType(state.prints, printId)
        )
      });
      commit('setSelectedAreaForPrint', {
        printId: printId,
        area: _.head(helpers.getPrintAreas(state.prints, printId))
      });
      commit('setColorsForPrint', {
        printId: printId,
        colors: helpers.getPrintColors(state.prints, printId)
      });
      commit('setSelectedColorForPrint', {
        printId: printId,
        color: _.head(helpers.getPrintColors(state.prints, printId))
      });
      commit('setCopiesForPrint', {
        printId: printId,
        copies: helpers.getPrintCopies(state.prints, printId)
      });
      commit('setSelectedCopyForPrint', {
        printId: printId,
        copy: _.head(helpers.getPrintCopies(state.prints, printId))
      });
      dispatch('pushPrints', {
        productModel: rootState.settings.productModel,
        sessionId: rootState.settings.sessionId,
      });
    },
    removePrint ({state, rootState, commit}, printId) {
      const print = helpers.getPrint(state.prints, printId);
      if (!_.isUndefined(print.storedId)) {
        return HTTP()
          .delete(
            rootState.settings.siteId
            + '/' + rootState.settings.sessionId
            + '/prints/'
            + print.storedId
          ).then(({ data }) => {
            commit('setPrints', _.filter(state.prints, (item) => {
              return item.id != printId;
            }));
          });
      } else {
        commit('setPrints', _.filter(state.prints, (item) => {
          return item.id != printId;
        }));
      }
    },
    clearPrints ({commit}) {
      commit('setPrints', []);
    }
  },
  mutations: {
    pushPrintToPrints(state, print) {
      state.prints.push(print);
    },
    setPrints(state, prints) {
      state.prints = prints;
    },
    setSelectedApplicationTypeForPrint(state, params) {
      state.prints = helpers.updatePrint(
        state.prints,
        'id',
        params.printId,
        'selectedApplicationType',
        params.applicationType
      );
    },
    setAreasForPrint(state, params) { // {printId, areas}
      state.prints = helpers.updatePrint(
        state.prints,
        'id',
        params.printId,
        'areas',
        params.areas
      );
    },
    setSelectedAreaForPrint(state, params) { // {printId, area}
      state.prints = helpers.updatePrint(
        state.prints,
        'id',
        params.printId,
        'selectedArea',
        params.area
      );
    },
    setColorsForPrint(state, params) { // {printId, colors}
      state.prints = helpers.updatePrint(
        state.prints,
        'id',
        params.printId,
        'colors',
        params.colors
      );
    },
    setSelectedColorForPrint(state, params) { // {printId, color}
      state.prints = helpers.updatePrint(
        state.prints,
        'id',
        params.printId,
        'selectedColor',
        params.color
      );
    },
    setCopiesForPrint(state, params) { // {printId, copies}
      state.prints = helpers.updatePrint(
        state.prints,
        'id',
        params.printId,
        'copies',
        params.copies
      );
    },
    setSelectedCopyForPrint(state, params) { // {printId, copy}
      state.prints = helpers.updatePrint(
        state.prints,
        'id',
        params.printId,
        'selectedCopy',
        params.copy
      );
    },
    setPrintClose(state, printId) {
      if (helpers.getPrintIsClose(state.prints, printId)) {
        state.prints = helpers.updatePrint(
          state.prints,
          'id',
          printId,
          'isClose',
          false
        );
      } else {
        state.prints = helpers.updatePrint(
          state.prints,
          'id',
          printId,
          'isClose',
          true
        );
      }
    },
  },
};
