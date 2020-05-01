<template>
  <div>
    <div class="d-flex">
      <div class="title mb-2">
        Latest Log
      </div>
      <v-spacer></v-spacer>
      <v-btn class="mb-2" right @click="getLog()" icon>
        <v-icon :class="{ spin: !latestLog }">cached</v-icon>
      </v-btn>
    </div>
    <div class="grey lighten-3 pa-4 align-start" :style="{ height: logContainerHeight }">
      <v-progress-circular v-if="!latestLog" indeterminate color="primary" size="24"></v-progress-circular>
      <pre v-if="latestLog">{{ latestLog }}</pre>
    </div>
  </div>
</template>

<script>
import * as api from '@/lib/api';

export default {
  name: 'LatestLog',
  components: {},
  data: function() {
    return {
      logContainerHeight: typeof this.height === 'number' ? `${this.height}px` : this.height,
      latestLog: null
    };
  },
  props: {
    height: {
      type: [Number, String],
      default: 200
    }
  },
  created() {
    this.init();
  },
  methods: {
    init() {
      this.getLog();
    },
    async getLog() {
      this.latestLog = null;
      const latestLog = await api.tailLog();
      this.latestLog = latestLog;
    }
  }
};
</script>

<style lang="scss" scoped>
pre {
  font-size: 0.875rem;
  height: 100%;
  overflow: scroll;
}
</style>
