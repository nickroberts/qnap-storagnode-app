import { getStatus, start, stop } from '../../lib/api';
import {
  SET_LOADING_STATUS,
  LOAD_STATUS_SUCCESS,
  LOAD_STATUS_FAILURE,
  SET_NODE_STATUS,
  START_NODE_SUCCESS,
  START_NODE_FAILURE,
  STOP_NODE_SUCCESS,
  STOP_NODE_FAILURE
} from './mutation-types';

export const status = {
  namespaced: true,
  state: () => ({
    data: null,
    loading: false,
    loadError: null,
    nodeStatus: null,
    nodeStatusError: null
  }),
  mutations: {
    [SET_LOADING_STATUS](state, status) {
      state.loading = status;
    },
    [LOAD_STATUS_SUCCESS](state, config) {
      state.data = config;
      state.loadError = null;
    },
    [LOAD_STATUS_FAILURE](state, e) {
      state.data = null;
      state.loadError = e;
    },
    [SET_NODE_STATUS](state, status) {
      state.nodeStatus = status;
    },
    [START_NODE_SUCCESS](state) {
      state.nodeStatusError = null;
    },
    [START_NODE_FAILURE](state, e) {
      state.nodeStatusError = e;
    },
    [STOP_NODE_SUCCESS](state) {
      state.nodeStatusError = null;
    },
    [STOP_NODE_FAILURE](state, e) {
      state.nodeStatusError = e;
    }
  },
  actions: {
    async loadStatus({ commit }) {
      console.log('loadStatus');
      commit(SET_LOADING_STATUS, true);
      try {
        const status = await getStatus();
        commit(LOAD_STATUS_SUCCESS, status);
      } catch (e) {
        commit(LOAD_STATUS_FAILURE, e);
      } finally {
        commit(SET_LOADING_STATUS, false);
      }
    },
    async startNode({ commit, dispatch }) {
      console.log('startNode');
      commit(SET_NODE_STATUS, 'STARTING');
      try {
        await start();
        commit(SET_NODE_STATUS, 'RUNNING');
        commit(START_NODE_SUCCESS);
      } catch (e) {
        commit(SET_NODE_STATUS, 'FAILED');
        commit(START_NODE_FAILURE, e);
      } finally {
        await dispatch('loadStatus');
      }
    },
    async stopNode({ commit, dispatch }) {
      console.log('stopNode');
      commit(SET_NODE_STATUS, 'STOPPING');
      try {
        await stop();
        commit(SET_NODE_STATUS, 'STOPPED');
        commit(STOP_NODE_SUCCESS);
      } catch (e) {
        commit(SET_NODE_STATUS, 'FAILED');
        commit(STOP_NODE_FAILURE, e);
      } finally {
        await dispatch('loadStatus');
      }
    },
    async restartNode({ commit, dispatch }) {
      console.log('restartNode');
      commit(SET_NODE_STATUS, 'RESTARTING');
      try {
        await dispatch('stopNode');
        await dispatch('startNode');
      } catch (e) {
        commit(SET_NODE_STATUS, 'FAILED');
        commit(STOP_NODE_FAILURE, e);
      } finally {
        await dispatch('loadStatus');
      }
    }
  }
};
