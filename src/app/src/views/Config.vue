<template>
  <v-container fluid class="config">
    <v-row>
      <v-col cols="12">
        <h1>Setup</h1>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="6">
        <v-card outlined tile class="fill-height">
          <div
            class="d-flex justify-start justify-md-space-between align-start pa-4 flex-column flex-md-row fill-height"
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
              <v-progress-circular
                v-if="!status"
                indeterminate
                color="primary"
                size="24"
              ></v-progress-circular>
            </div>
            <div v-if="status" class="mt-4 mt-md-0 align-self-center">
              <v-btn v-if="!status.Status" color="primary" large>Start</v-btn>
              <v-btn v-if="status.Status" color="red" dark large>Stop</v-btn>
            </div>
          </div>
        </v-card>
      </v-col>

      <v-col cols="12" md="6">
        <v-card outlined tile class="fill-height">
          <div
            class="d-flex justify-start justify-md-space-between align-start pa-4 flex-column flex-md-row fill-height"
          >
            <div>
              <div class="title">
                Version
              </div>
              <div class="subtitle">
                <span>Latest version installed</span>
              </div>
            </div>
            <div class="mt-4 mt-md-0 align-self-center">
              <v-btn color="primary" large>Update Node</v-btn>
            </div>
          </div>
        </v-card>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <v-card class="pa-4" outlined tile>
          <div class="d-flex">
            <div class="title mb-2">
              Latest Log
            </div>
            <v-spacer></v-spacer>
            <v-btn color="primary" class="mb-2" right @click="getLog()" icon>
              <v-icon :class="{ spin: !latestLog }">cached</v-icon>
            </v-btn>
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
          :value="configData.identityPath"
          info="Every node is required to have a unique identifier on the network. If you haven't already, get an authorization token. Please get the authorization token and create identity on host machine other than NAS"
        >
          <IdentityPathDialog :data="configData" :on-save="save" />
        </ConfigCard>
      </v-col>
      <v-col cols="12" md="6">
        <ConfigCard
          icon="settings_ethernet"
          title="Port Forwarding"
          :value="configData.hostname"
          info="How a storage node communicates with others on the Storj network, even though it is behind a router. You need a dynamic DNS service to ensure your storage node is connected"
        >
          <HostnameDialog :data="configData" :on-save="save" />
        </ConfigCard>
      </v-col>

      <v-col cols="12" md="6">
        <ConfigCard
          icon="dns"
          title="Ethereum Wallet Address"
          :value="configData.walletAddress"
          info="In order to recieve and hold your STORJ token payouts, you need an ERC-20 compatible wallet address"
        >
          <WalletAddressDialog :data="configData" :on-save="save" />
        </ConfigCard>
      </v-col>
      <v-col cols="12" md="6">
        <ConfigCard
          icon="bar_chart"
          title="Storage Allocation"
          :value="`${configData.storageAllocation} GB`"
          info="How much disk space you want to allocate to the Storj network"
        >
          <StorageAllocationDialog :data="configData" :on-save="save" />
        </ConfigCard>
      </v-col>

      <v-col cols="12" md="6">
        <ConfigCard
          icon="email"
          title="Email Address"
          :value="configData.emailAddress"
          info="Join thousands of Node Operators around the world by getting Node status updates from Storj Labs."
        >
          <EmailAddressDialog :data="configData" :on-save="save" />
        </ConfigCard>
      </v-col>
      <v-col cols="12" md="6">
        <ConfigCard
          icon="storage"
          title="Storage Directory"
          :value="configData.storageDirectory"
          info="The local directory where you want files to be stored on your hard drive for the network"
        >
          <StorageDirectoryDialog :data="configData" :on-save="save" />
        </ConfigCard>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import axios from "axios";
import ConfigCard from "@/components/ConfigCard.vue";
import StorageAllocationDialog from "@/components/config/StorageAllocationDialog.vue";
import EmailAddressDialog from "@/components/config/EmailAddressDialog.vue";
import IdentityPathDialog from "@/components/config/IdentityPathDialog.vue";
import HostnameDialog from "@/components/config/HostnameDialog.vue";
import StorageDirectoryDialog from "@/components/config/StorageDirectoryDialog.vue";
import WalletAddressDialog from "@/components/config/WalletAddressDialog.vue";

export default {
  name: "Config",
  components: {
    ConfigCard,
    StorageAllocationDialog,
    EmailAddressDialog,
    IdentityPathDialog,
    HostnameDialog,
    StorageDirectoryDialog,
    WalletAddressDialog,
  },
  data: () => ({
    configData: null,
    latestLog: null,
    status: null,
    dialog: false,
  }),
  created() {
    this.init();
  },
  methods: {
    init() {
      axios
        .get("/api.php", { params: { action: "config" } })
        .then(({ data }) => {
          this.configData = data.data;
        });
      this.getLog();
      axios
        .get("/api.php", { params: { action: "check" } })
        .then(({ data }) => {
          this.status = data.data.output;
        });
    },
    getLog() {
      this.latestLog = null;
      axios
        .get("/api.php", { params: { action: "tail", lines: 50 } })
        .then(({ data }) => {
          this.latestLog = data.data.output;
        });
    },
    save(data) {
      axios.post("/api.php", { action: "config", data }).then(({ data }) => {
        this.configData = data.data;
      });
    },
  },
};
</script>

<style lang="scss" scoped>
pre {
  height: 200px;
  overflow: scroll;
}
@keyframes rotation {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(359deg);
  }
}
.spin {
  animation: rotation 2s infinite linear;
}
</style>
