<template>
  <v-card outlined tile class="fill-height pa-4">
    <div class="title">Node Status</div>
    <v-progress-circular v-if="statusLoading" indeterminate color="primary" size="24"></v-progress-circular>
    <div v-if="!statusLoading" class="d-flex justify-start justify-space-between align-start">
      <div>
        <div>
          <v-list-item class="px-0">
            <v-list-item-content>
              <v-list-item-title>
                <span v-if="statusData.status !== 'online'" class="red--text font-weight-bold">
                  Offline:
                </span>
                <span v-if="statusData.status === 'online'" class="green--text font-weight-bold">
                  Online:
                </span>
              </v-list-item-title>
              <v-list-item-subtitle v-if="statusData.dockerInfo">{{
                statusData.dockerInfo.status
              }}</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>

          <v-list-item class="px-0" v-if="statusData.dockerInfo">
            <v-list-item-content>
              <v-list-item-title>Container Image:</v-list-item-title>
              <v-list-item-subtitle>{{ statusData.dockerInfo.image }}</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>

          <v-list-item class="px-0" v-if="statusData.dockerInfo">
            <v-list-item-content>
              <v-list-item-title>Container Name:</v-list-item-title>
              <v-list-item-subtitle>{{ statusData.dockerInfo.names }}</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </div>
      </div>
      <div v-if="statusData.status === 'online'" class="d-flex">
        <v-btn class="d-block" color="primary" x-large @click="restart()">Restart</v-btn>
        <v-btn class="d-block ml-4" color="red" outlined x-large @click="stop()">Stop</v-btn>
      </div>
      <v-btn v-if="statusData.status !== 'online'" color="primary" x-large @click="start()">Start</v-btn>
    </div>
    <!-- <pre v-if="!statusLoading">{{ JSON.stringify(statusData, null, 2) }}</pre> -->
  </v-card>
</template>

<script>
import { mapActions, mapState } from 'vuex';
export default {
  name: 'StatusCard',
  components: {},
  props: {
    status: Object
  },
  computed: {
    ...mapState({
      statusData: state => state.status.data,
      statusLoading: state => state.status.loading
    })
  },
  methods: {
    async start() {
      await this['status/startNode']();
    },
    async stop() {
      await this['status/stopNode']();
    },
    async restart() {
      await this['status/restartNode']();
    },
    ...mapActions(['status/startNode', 'status/stopNode', 'status/restartNode'])
  }
};
</script>
