import Vue from 'vue';
import HTTP from '../http.js';
import helpers from '../helpers.js';

export default {
  namespaced: true,
  state: {
      printsDataId: '',
      comment: '',
      isFileLink: false,
      fileUrl: '',
      remoteFileUrl: '',
  },
  actions: {
      fetchDuringPrintsData({ commit, state, rootState, dispatch }) {
        return HTTP().get(
            rootState.settings.siteId
            + '/' + rootState.settings.sessionId
            + '/printsdata/during/'
            +  rootState.settings.productModel
        ).then((data) => {
            if (_.isEmpty(data.data)) {
                dispatch('pushPrintsData');
            } else {
                commit('setPrintDataId', data.data.id);
                commit('setComment', data.data.comment);
                if (data.data.isFileLink == 0) {
                    commit('setIsFileLink', false);
                }
                if (data.data.is_file_link == '1') {
                    commit('setIsFileLink', true);
                }
                commit('setRemoteFileUrl', data.data.remote_file_url);
                commit('setFileUrl', data.data.file_url);
            }
        });
      },
      pushPrintsData({ commit, state, rootState, dispatch }) {
          return HTTP().post(
              rootState.settings.siteId
              + '/' + rootState.settings.sessionId
              + '/' + rootState.settings.productModel
              + '/printsdata',
              {
                  printsDataId: state.printsDataId || null,
                  comment: state.comment || '',
                  isFileLink: state.isFileLink || false,
                  fileUrl: state.fileUrl || '',
                  remoteFileUrl: state.remoteFileUrl || ''
              }
          ).then((data) => {
              commit('setPrintDataId', data.data);
          });
      }
  },
  mutations: {
      setPrintDataId(state, id) {
          state.printsDataId = id;
      },
      setComment(state, comment) {
          state.comment = comment;
      },
      setIsFileLink(state, fileLink) {
          state.isFileLink = fileLink;
      },
      setRemoteFileUrl(state, remoteFileUrl) {
          state.remoteFileUrl = remoteFileUrl;
      },
      setFileUrl(state, fileUrl) {
          state.fileUrl = fileUrl;
      },
  },
};
