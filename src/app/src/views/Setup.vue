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

    <v-row>
      <v-col cols="12" md="6">
        <StatusCard />
      </v-col>
      <v-col cols="12" md="6">
        <VersionCard />
      </v-col>
    </v-row>

    <v-row v-if="configData">
      <v-col cols="12" md="6">
        <ConfigCard
          icon="fingerprint"
          title="Identity"
          name="identityPath"
          info="Every node is required to have a unique identifier on the network. If you haven't already, get an authorization token. Please get the authorization token and create identity on host machine other than NAS"
        >
          <IdentityPathDialog :data="configData" :on-save="save" />
        </ConfigCard>
      </v-col>

      <v-col cols="12" md="6">
        <ConfigCard
          icon="settings_ethernet"
          title="Port Forwarding"
          name="hostname"
          info="How a storage node communicates with others on the Storj network, even though it is behind a router. You need a dynamic DNS service to ensure your storage node is connected"
        >
          <HostnameDialog :data="configData" :on-save="save" />
        </ConfigCard>
      </v-col>

      <v-col cols="12" md="6">
        <ConfigCard
          icon="dns"
          title="Ethereum Wallet Address"
          name="walletAddress"
          info="In order to recieve and hold your STORJ token payouts, you need an ERC-20 compatible wallet address"
        >
          <WalletAddressDialog :data="configData" :on-save="save" />
        </ConfigCard>
      </v-col>

      <v-col cols="12" md="6">
        <ConfigCard
          icon="bar_chart"
          title="Storage Allocation"
          name="storageAllocation"
          append="GB"
          info="How much disk space you want to allocate to the Storj network"
        >
          <StorageAllocationDialog :data="configData" :on-save="save" />
        </ConfigCard>
      </v-col>

      <v-col cols="12" md="6">
        <ConfigCard
          icon="email"
          title="Email Address"
          name="emailAddress"
          info="Join thousands of Node Operators around the world by getting Node status updates from Storj Labs."
        >
          <EmailAddressDialog :data="configData" :on-save="save" />
        </ConfigCard>
      </v-col>

      <v-col cols="12" md="6">
        <ConfigCard
          icon="storage"
          title="Storage Directory"
          name="storageDirectory"
          info="The local directory where you want files to be stored on your hard drive for the network"
        >
          <StorageDirectoryDialog :data="configData" :on-save="save" />
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
import { mapActions, mapState } from 'vuex';
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
    // config: null,
    latestLog: null,
    loading: false,
    status: null,
    dialog: false
  }),
  created() {
    this.init();
  },
  computed: {
    ...mapState({
      configData: state => state.config.data,
      configLoading: state => state.config.loading
    })
  },
  methods: {
    async start() {
      this.loading = true;
      await api.start();
      await this.init();
    },
    async stop() {
      this.loading = true;
      await api.stop();
      await this.init();
    },
    async restart() {
      this.loading = true;
      await api.restart();
      await this.init();
    },
    async update() {
      this.loading = true;
      await api.update();
      await this.init();
    },
    // async getConfig() {
    //   this.loading = true;
    //   const response = await api.getConfig();
    //   this.config = response;
    // },
    async getStatus() {
      const response = await api.getStatus();
      this.status = response;
    },
    async init() {
      // try {
      //   this.loading = true;
      //   await Promise.all(this.getStatus());
      //   // await Promise.all(this.getConfig(), this.getStatus());
      //   this.loading = false;
      // } catch (e) {
      //   console.error(e);
      // }
    },
    async save(configData) {
      await this['config/saveConfig'](configData);
      // this.loading = true;
      // await api.saveConfig(configData);
      // await this.init();
      // this.loading = false;
    },
    ...mapActions(['config/saveConfig'])
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
