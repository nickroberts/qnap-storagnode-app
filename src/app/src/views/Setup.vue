<template>
  <v-container fluid class="config">
    <v-row>
      <v-col cols="12">
        <div class="d-flex align-baseline">
          <h1>Setup</h1>
          <v-spacer></v-spacer>
          <router-link to="/">
            Documentation
          </router-link>
        </div>
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
          <v-progress-circular v-if="!status" indeterminate color="primary" size="24"></v-progress-circular>
          <div v-if="status" class="d-flex justify-start justify-space-between align-center pa-4">
            <StatusCard :status="status" />
            <div v-if="status.status === 'online'" class="d-flex">
              <v-btn class="d-block" color="primary" x-large @click="restart()">Restart</v-btn>
              <v-btn class="d-block ml-4" color="red" outlined x-large @click="stop()">Stop</v-btn>
            </div>
            <v-btn v-if="status.status !== 'online'" color="primary" x-large @click="start()">Start</v-btn>
          </div>
        </v-card>
      </v-col>

      <v-col cols="12" md="6">
        <v-card outlined tile class="fill-height">
          <v-progress-circular v-if="!status" indeterminate color="primary" size="24"></v-progress-circular>
          <div v-if="status" class="d-flex justify-start justify-space-between align-center pa-4">
            <VersionCard :status="status" />
            <div>
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

    <v-row v-if="config">
      <v-col cols="12" md="6">
        <ConfigCard
          icon="fingerprint"
          title="Identity"
          :value="config.identityPath"
          info="Every node is required to have a unique identifier on the network. If you haven't already, get an authorization token. Please get the authorization token and create identity on host machine other than NAS"
        >
          <IdentityPathDialog :data="config" :on-save="save" />
        </ConfigCard>
      </v-col>
      <v-col cols="12" md="6">
        <ConfigCard
          icon="settings_ethernet"
          title="Port Forwarding"
          :value="config.hostname"
          info="How a storage node communicates with others on the Storj network, even though it is behind a router. You need a dynamic DNS service to ensure your storage node is connected"
        >
          <HostnameDialog :data="config" :on-save="save" />
        </ConfigCard>
      </v-col>

      <v-col cols="12" md="6">
        <ConfigCard
          icon="dns"
          title="Ethereum Wallet Address"
          :value="config.walletAddress"
          info="In order to recieve and hold your STORJ token payouts, you need an ERC-20 compatible wallet address"
        >
          <WalletAddressDialog :data="config" :on-save="save" />
        </ConfigCard>
      </v-col>
      <v-col cols="12" md="6">
        <ConfigCard
          icon="bar_chart"
          title="Storage Allocation"
          :value="config.storageAllocation"
          append="GB"
          info="How much disk space you want to allocate to the Storj network"
        >
          <StorageAllocationDialog :data="config" :on-save="save" />
        </ConfigCard>
      </v-col>

      <v-col cols="12" md="6">
        <ConfigCard
          icon="email"
          title="Email Address"
          :value="config.emailAddress"
          info="Join thousands of Node Operators around the world by getting Node status updates from Storj Labs."
        >
          <EmailAddressDialog :data="config" :on-save="save" />
        </ConfigCard>
      </v-col>
      <v-col cols="12" md="6">
        <ConfigCard
          icon="storage"
          title="Storage Directory"
          :value="config.storageDirectory"
          info="The local directory where you want files to be stored on your hard drive for the network"
        >
          <StorageDirectoryDialog :data="config" :on-save="save" />
        </ConfigCard>
      </v-col>
    </v-row>

    <v-row v-if="!loading">
      <v-col cols="12">
        <v-card class="pa-4" outlined tile>
          <LatestLog />
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import * as api from '@/lib/api';
import StatusCard from '@/components/StatusCard.vue';
import VersionCard from '@/components/VersionCard.vue';
import ConfigCard from '@/components/ConfigCard.vue';
import LatestLog from '@/components/LatestLog.vue';
import StorageAllocationDialog from '@/components/config/StorageAllocationDialog.vue';
import EmailAddressDialog from '@/components/config/EmailAddressDialog.vue';
import IdentityPathDialog from '@/components/config/IdentityPathDialog.vue';
import HostnameDialog from '@/components/config/HostnameDialog.vue';
import StorageDirectoryDialog from '@/components/config/StorageDirectoryDialog.vue';
import WalletAddressDialog from '@/components/config/WalletAddressDialog.vue';

export default {
  name: 'Setup',
  components: {
    StatusCard,
    VersionCard,
    ConfigCard,
    LatestLog,
    StorageAllocationDialog,
    EmailAddressDialog,
    IdentityPathDialog,
    HostnameDialog,
    StorageDirectoryDialog,
    WalletAddressDialog
  },
  data: () => ({
    config: null,
    latestLog: null,
    loading: false,
    status: null,
    dialog: false
  }),
  created() {
    this.init();
  },
  methods: {
    async start() {
      await api.start();
      await this.init();
    },
    async stop() {
      await api.stop();
      await this.init();
    },
    async restart() {
      await api.restart();
      await this.init();
    },
    async update() {
      await api.update();
      await this.init();
    },
    async getConfig() {
      const response = await api.getConfig();
      this.config = response;
    },
    async getStatus() {
      const response = await api.getStatus();
      this.status = response;
    },
    async init() {
      this.loading = true;
      await Promise.all(this.getConfig(), this.getStatus());
      this.loading = false;
    },
    async save(configData) {
      this.loading = true;
      await api.saveConfig(configData);
      await this.init();
      this.loading = false;
    }
  }
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
