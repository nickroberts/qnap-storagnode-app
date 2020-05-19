<template>
  <v-card outlined tile class="fill-height pa-4">
    <div class="title">Storage Node Version</div>
    <v-progress-circular v-if="statusLoading" indeterminate color="primary" size="24"></v-progress-circular>
    <div v-if="!statusLoading">
      <div class="d-flex justify-start justify-space-between align-start">
        <div>
          <v-list-item class="px-0">
            <v-list-item-content v-if="statusData.status !== 'online'">
              <v-list-item-title>Start the storage node to show the version.</v-list-item-title>
            </v-list-item-content>
            <v-list-item-content v-if="statusData.status === 'online'">
              <v-list-item-title>Version:</v-list-item-title>
              <v-list-item-subtitle v-if="statusData.versionInfo">{{
                statusData.versionInfo.version
              }}</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </div>
        <div class="d-flex">
          <v-btn v-if="statusData.status === 'online'" color="primary" large @click="update()">Update Node</v-btn>
          <v-btn class="ml-4" color="primary" :href="statusData.statsHost" target="_blank" x-large outlined>
            <v-icon left dark>open_in_new</v-icon>
            View Storj Stats
          </v-btn>
        </div>
      </div>
    </div>
    <!-- <pre v-if="!statusLoading">{{ JSON.stringify(statusData, null, 2) }}</pre> -->
  </v-card>
</template>

<script>
import { mapState } from 'vuex';
export default {
  name: 'VersionCard',
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
    update() {
      console.log('update');
    }
  }
};
</script>
