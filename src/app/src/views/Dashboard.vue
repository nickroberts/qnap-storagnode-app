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
          <v-progress-circular
            v-if="!status"
            indeterminate
            color="primary"
            size="24"
          ></v-progress-circular>
          <div
            class="d-flex justify-start justify-md-space-between align-start align-md-center pa-4 flex-column flex-md-row fill-height"
            v-if="status"
          >
            <div>
              <div class="title">Node Status</div>
              <div class="subtitle">
                Container name:&nbsp;<strong>{{ status.containerName }}</strong>
              </div>
              <div class="subtitle">
                <span v-if="!status.Status" class="red--text font-weight-bold"
                  >Offline</span
                >
                <span v-if="status.Status" class="green--text font-weight-bold"
                  >Online</span
                >
              </div>
            </div>
            <div class="mt-4 mt-md-0 align-self-center">
              <v-btn v-if="!status.Status" color="primary" large>Start</v-btn>
              <v-btn v-if="status.Status" color="red" dark large>Stop</v-btn>
            </div>
          </div>
        </v-card>
      </v-col>

      <v-col cols="12" md="6">
        <v-card outlined tile class="fill-height">
          <v-progress-circular
            v-if="!status"
            indeterminate
            color="primary"
            size="24"
          ></v-progress-circular>
          <div
            class="d-flex justify-start justify-md-space-between align-start pa-4 flex-column flex-md-row fill-height"
            v-if="status"
          >
            <v-spacer></v-spacer>
            <div class="mt-4 mt-md-0 align-self-center">
              <v-btn
                color="primary"
                :href="status.statsHost"
                target="_blank"
                large
              >
                <v-icon left dark>open_in_new</v-icon>
                View Storj Stats
              </v-btn>
            </div>
          </div>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import axios from "axios";

export default {
  name: "Dashboard",
  components: {},
  data: () => ({
    status: null,
  }),
  methods: {
    getStatus() {
      axios
        .get("/api.php", { params: { action: "check" } })
        .then(({ data }) => {
          this.status = data.data.output;
        });
    },
  },
  created() {
    this.getStatus();
  },
  watch: {
    $route: "getStatus",
  },
};
</script>
