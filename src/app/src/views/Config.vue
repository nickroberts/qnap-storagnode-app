<template>
  <v-container fluid class="config">
    <v-row>
      <v-col cols="12">
        <h1>Setup</h1>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="6">
        <v-card outlined tile>
          <div class="d-flex justify-space-between align-center pa-4">
            <div>
              <div class="title">
                Node Status
              </div>
              <div v-if="status" class="subtitle">
                <span v-if="!status.Status" class="red--text font-weight-bold"
                  >Offline</span
                >
                <span v-if="status.Status" class="green--text font-weight-bold"
                  >Online</span
                >
              </div>
              <v-progress-circular
                v-if="!status"
                indeterminate
                color="primary"
                size="24"
              ></v-progress-circular>
            </div>
            <div v-if="status">
              <v-btn v-if="!status.Status" color="primary" large>Start</v-btn>
              <v-btn v-if="status.Status" color="red" dark large>Stop</v-btn>
            </div>
          </div>
        </v-card>
      </v-col>

      <v-col cols="12" md="6">
        <v-card outlined tile>
          <div class="d-flex justify-space-between align-center pa-4">
            <div>
              <div class="title">
                Version
              </div>
              <div class="subtitle">
                <span>Latest version installed</span>
              </div>
            </div>
            <div>
              <v-btn color="primary" large>Update Node</v-btn>
            </div>
          </div>
        </v-card>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <v-card class="pa-4" outlined tile>
          <div class="title mb-2">
            Latest Log
          </div>
          <div class="grey lighten-3 pa-4" v-if="latestLog">
            <pre>{{ latestLog }}</pre>
          </div>
          <v-progress-circular
            v-if="!latestLog"
            indeterminate
            color="primary"
            size="24"
          ></v-progress-circular>
        </v-card>
      </v-col>
    </v-row>

    <v-row v-if="!configData">
      <v-col cols="12">
        <v-card class="pa-4" outlined tile>
          <v-progress-circular
            indeterminate
            color="primary"
            size="24"
          ></v-progress-circular>
        </v-card>
      </v-col>
    </v-row>

    <v-row v-if="configData">
      <v-col cols="12" md="6">
        <ConfigCard
          icon="fingerprint"
          title="Identity"
          :value="configData.Identity"
          info="some info about this"
        >
          <v-btn color="primary" outlined>Edit</v-btn>
        </ConfigCard>
      </v-col>
      <v-col cols="12" md="6">
        <ConfigCard
          icon="storage"
          title="Port Forwarding"
          :value="configData.Port"
          info="some info about this"
        >
          <v-btn color="primary" outlined>Edit</v-btn>
        </ConfigCard>
      </v-col>

      <v-col cols="12" md="6">
        <ConfigCard
          icon="fingerprint"
          title="Ethereum Wallet Address"
          :value="configData.Wallet"
          info="some info about this"
        >
          <v-btn color="primary" outlined>Edit</v-btn>
        </ConfigCard>
      </v-col>
      <v-col cols="12" md="6">
        <ConfigCard
          icon="storage"
          title="Storage Allocation"
          :value="configData.Allocation"
          info="some info about this"
        >
          <v-btn color="primary" outlined>Edit</v-btn>
        </ConfigCard>
      </v-col>

      <v-col cols="12" md="6">
        <ConfigCard
          icon="fingerprint"
          title="Email Address"
          :value="configData.Email"
          info="some info about this"
        >
          <v-btn color="primary" outlined>Edit</v-btn>
        </ConfigCard>
      </v-col>
      <v-col cols="12" md="6">
        <ConfigCard
          icon="storage"
          title="Storage Directory"
          :value="configData.Directory"
          info="some info about this"
        >
          <v-btn color="primary" outlined>Edit</v-btn>
        </ConfigCard>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import axios from "axios";
import ConfigCard from "@/components/ConfigCard.vue";

export default {
  name: "Config",
  components: {
    ConfigCard,
  },
  data: () => ({
    configData: null,
    latestLog: null,
    status: null,
  }),
  created() {
    this.init();
  },
  methods: {
    init() {
      axios
        .get("/api.php", { params: { action: "config" } })
        .then(({ data }) => {
          console.log("config", data);
          this.configData = data.data;
        });
      axios
        .get("/api.php", { params: { action: "tail", lines: 50 } })
        .then(({ data }) => {
          console.log("tail", data);
          this.latestLog = data.data.output;
        });
      axios
        .get("/api.php", { params: { action: "check" } })
        .then(({ data }) => {
          console.log("check", data);
          this.status = data.data.output;
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.v-btn {
  min-width: 160px !important;
}

pre {
  height: 200px;
  overflow: scroll;
}
</style>
