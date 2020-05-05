<template>
  <v-container fluid class="config">
    <v-row>
      <v-col cols="12">
        <h1>Dashboard</h1>
      </v-col>
    </v-row>

    <v-row v-if="loading">
      <v-col cols="12">
        <v-card class="pa-4" outlined tile>
          <v-progress-circular indeterminate color="primary" size="24"></v-progress-circular>
        </v-card>
      </v-col>
    </v-row>

    <v-row v-if="!loading">
      <v-col cols="12" md="6">
        <v-card outlined tile class="fill-height">
          <div class="d-flex justify-start justify-space-between align-start pa-4 fill-height">
            <StatusCard :status="status" />
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
          <div class="d-flex justify-start justify-space-between align-start pa-4 fill-height">
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

    <v-row v-if="!loading">
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
    loading: false,
    status: null
  }),
  methods: {
    async start() {
      await api.start();
      await this.getStatus();
    },
    async stop() {
      await api.stop();
      await this.getStatus();
    },
    async restart() {
      await api.restart();
      await this.getStatus();
    },
    async update() {
      await api.update();
      await this.getStatus();
    },
    async getStatus() {
      this.loading = true;
      const response = await api.getStatus();
      this.status = response;
      this.loading = false;
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
