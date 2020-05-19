import { getConfig, saveConfig } from '../../lib/api';
import {
  SET_LOADING_STATUS,
  LOAD_CONFIG_SUCCESS,
  LOAD_CONFIG_FAILURE,
  SAVE_CONFIG_SUCCESS,
  SAVE_CONFIG_FAILURE
} from './mutation-types';

export const config = {
  namespaced: true,
  state: () => ({
    data: null,
    loading: false,
    loadError: null,
    saveError: null
  }),
  mutations: {
    [SET_LOADING_STATUS](state, status) {
      state.loading = status;
    },
    [LOAD_CONFIG_SUCCESS](state, config) {
      state.data = config;
      state.loadError = null;
    },
    [LOAD_CONFIG_FAILURE](state, e) {
      state.data = null;
      state.loadError = e;
    },
    [SAVE_CONFIG_SUCCESS](state, config) {
      state.data = config;
      state.saveError = null;
    },
    [SAVE_CONFIG_FAILURE](state, e) {
      state.saveError = e;
    }
  },
  actions: {
    async loadConfig({ commit }) {
      commit(SET_LOADING_STATUS, true);
      try {
        const config = await getConfig();
        commit(LOAD_CONFIG_SUCCESS, config);
      } catch (e) {
        commit(LOAD_CONFIG_FAILURE, e);
      } finally {
        commit(SET_LOADING_STATUS, false);
      }
    },
    async saveConfig({ commit }, payload) {
      commit(SET_LOADING_STATUS, true);
      try {
        const config = await saveConfig(payload);
        commit(SAVE_CONFIG_SUCCESS, config);
      } catch (e) {
        commit(SAVE_CONFIG_FAILURE, e);
      } finally {
        commit(SET_LOADING_STATUS, false);
      }
    }
  }
};
