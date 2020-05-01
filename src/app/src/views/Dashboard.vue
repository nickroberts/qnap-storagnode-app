<template>
  <v-container fluid class="config">
    <v-row>
      <v-col cols="12">
        <h1>Dashboard</h1>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="6">
        <v-card outlined tile class="fill-height">
          <v-progress-circular v-if="!status" indeterminate color="primary" size="24"></v-progress-circular>
          <div v-if="status" class="d-flex justify-start justify-space-between align-start pa-4 fill-height">
            <StatusCard :status="status"
              ><v-list-item class="px-0">
                <v-list-item-content>
                  <v-list-item-title>Container Image:</v-list-item-title>
                  <v-list-item-subtitle>{{ status.dockerInfo.image }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>

              <v-list-item class="px-0">
                <v-list-item-content>
                  <v-list-item-title>Container Name:</v-list-item-title>
                  <v-list-item-subtitle>{{ status.dockerInfo.names }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </StatusCard>
            <div>
              <div v-if="status.status === 'online'" class="d-flex">
                <v-btn class="d-block" color="primary" x-large @click="restart()">Restart</v-btn>
                <v-btn class="d-block ml-4" color="red" outlined x-large @click="stop()">Stop</v-btn>
              </div>
              <v-btn v-if="status.status !== 'online'" color="primary" x-large @click="start()">Start</v-btn>
            </div>
          </div>
        </v-card>
      </v-col>

      <v-col cols="12" md="6">
        <v-card outlined tile class="fill-height">
          <v-progress-circular v-if="!status" indeterminate color="primary" size="24"></v-progress-circular>
          <div v-if="status" class="d-flex justify-start justify-space-between align-start pa-4 fill-height">
            <VersionCard :status="status" />
            <div class="d-flex">
              <v-btn color="primary" large @click="update()">Update Node</v-btn>
              <v-btn class="ml-4" color="primary" :href="status.statsHost" target="_blank" x-large outlined>
                <v-icon left dark>open_in_new</v-icon>
                View Storj Stats
              </v-btn>
            </div>
          </div>
        </v-card>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <v-card class="pa-4" outlined tile>
          <LatestLog :height="500" />
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import * as api from '@/lib/api';
import StatusCard from '@/components/StatusCard.vue';
import VersionCard from '@/components/VersionCard.vue';
import LatestLog from '@/components/LatestLog.vue';

export default {
  name: 'Dashboard',
  components: {
    StatusCard,
    VersionCard,
    LatestLog
  },
  data: () => ({
    status: null
  }),
  methods: {
    start() {
      api.start();
    },
    stop() {
      api.stop();
    },
    restart() {
      api.restart();
    },
    update() {
      api.update();
    },
    async getStatus() {
      const response = await api.getStatus();
      this.status = response;
    }
  },
  created() {
    this.getStatus();
  },
  watch: {
    $route: 'getStatus'
  }
};
</script>
