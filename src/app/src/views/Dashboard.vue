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

    <v-row>
      <v-col cols="12" md="6">
        <StatusCard />
      </v-col>
      <v-col cols="12" md="6">
        <VersionCard />
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
      this.loading = true;
      await api.start();
      await this.getStatus();
    },
    async stop() {
      this.loading = true;
      await api.stop();
      await this.getStatus();
    },
    async restart() {
      this.loading = true;
      await api.restart();
      await this.getStatus();
    },
    async update() {
      this.loading = true;
      await api.update();
      await this.getStatus();
    }
    // async getStatus() {
    //   this.loading = true;
    //   const response = await api.getStatus();
    //   this.status = response;
    //   this.loading = false;
    // }
  },
  created() {
    // this.getStatus();
  },
  watch: {
    $route: 'getStatus'
  }
};
</script>
