<template>
  <v-form v-model="valid" ref="form" @submit.prevent="validate()">
    <h1>Configure Your External Port Forwarding</h1>

    <p>
      How a storage node communicates with others on the Storj network, even though it is behind a router. You need a
      dynamic DNS service to ensure your storage node is connected
    </p>

    <div class="form-control">
      <label>Host Address</label>
      <v-text-field
        v-model="hostname"
        :rules="hostnameRules"
        label="Host Address"
        placeholder="http://hostname.ddns.net:28967"
        required
        outlined
        single-line
      ></v-text-field>
    </div>

    <v-btn color="primary" x-large @click="validate()" :disabled="!valid">Continue</v-btn>
  </v-form>
</template>

<script>
import { hostnameRules } from '@/lib/validationRules';

export default {
  name: 'WizardHostname',
  components: {},
  data: function() {
    return {
      hostname: this.configData.hostname,
      hostnameRules,
      valid: false
    };
  },
  props: {
    configData: Object
  },
  methods: {
    complete() {
      const data = { ...this.configData, hostname: this.hostname };
      this.$emit('step-complete', data);
    },
    validate() {
      this.$refs.form.validate();
      if (this.valid) {
        this.complete();
      }
    }
  }
};
</script>

<style lang="scss" scoped></style>
