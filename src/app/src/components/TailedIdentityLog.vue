<template>
  <div class="mb-4" v-if="enable">
    <div class="log-container grey lighten-3 pa-4 mb-4 align-start">
      <pre>{{ latestLog }}</pre>
    </div>
    <v-progress-circular indeterminate color="primary" size="24"></v-progress-circular>
  </div>
</template>

<script>
import * as api from '@/lib/api';

export default {
  name: 'TailedIdentityLog',
  components: {},
  data: () => ({
    latestLog: null
  }),
  props: {
    enable: {
      type: Boolean,
      default: true
    }
  },
  methods: {
    async reloadLog() {
      const latestLog = await api.tailIdentityLog();
      this.latestLog = latestLog;
    },
    update: function() {
      if (this.enable && !this.timer) {
        this.reloadLog();
        this.timer = setInterval(() => this.reloadLog(), 10000);
      } else if (!this.enable) {
        clearInterval(this.timer);
      }
    }
  },
  watch: {
    enable: {
      immediate: true,
      handler: function() {
        this.update();
      }
    }
  }
};
</script>

<style lang="scss" scoped>
.log-container.grey {
  text-align: left;
}
pre {
  font-size: 0.875rem;
  height: 200px;
  overflow: scroll;
}
</style>
