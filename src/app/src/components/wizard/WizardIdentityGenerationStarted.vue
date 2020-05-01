<template>
  <div>
    <h1>Identity Generation Started</h1>

    <p>
      Creating identity can take several hours or even days, depending on your machines processing power &amp;
      probability. You will be able to track your progress after configuring the rest
    </p>

    <TailedIdentityLog class="mb-4" :enable="enable" />

    <v-btn color="primary" x-large @click="finish()">Finish</v-btn>
  </div>
</template>

<script>
import TailedIdentityLog from '@/components/TailedIdentityLog.vue';

export default {
  name: 'WizardIdentityGenerationStarted',
  components: {
    TailedIdentityLog
  },
  data: () => ({
    enable: true
  }),
  methods: {
    finish: function() {
      this.enable = false;
      this.$emit('step-complete');
    }
  }
};
</script>

<style lang="scss" scoped></style>
