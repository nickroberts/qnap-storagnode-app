<template>
  <v-form v-model="valid" ref="form" @submit.prevent="validate()">
    <h1>Setup Your Identity Path</h1>

    <p>
      Every node is required to have a unique identifier on the network. If you haven't already, get an authorization
      token. Please get the authorization token and create identity on host machine other than NAS
    </p>

    <div class="form-control">
      <label>Identity Path</label>
      <v-text-field
        v-model="identityPath"
        :rules="identityPathRules"
        label="Identity Path"
        placeholder="/path/to/identity"
        required
        outlined
        single-line
        :disabled="completed"
        :readonly="completed"
      ></v-text-field>
    </div>

    <TailedIdentityLog class="mb-4" :enable="enableLog" />

    <v-btn class="mr-2" color="primary" outlined x-large @click="$emit('no-identity')" :disabled="completed"
      >I don't have an identity</v-btn
    >

    <v-btn color="primary" x-large @click="validate()" :disabled="!valid">Finish</v-btn>
  </v-form>
</template>

<script>
import { identityPathRules } from '@/lib/validationRules';
import TailedIdentityLog from '@/components/TailedIdentityLog.vue';

export default {
  name: 'WizardIdentityPath',
  components: { TailedIdentityLog },
  data: function() {
    return {
      finished: false,
      identityPath: this.configData.identityPath,
      identityPathRules,
      valid: false
    };
  },
  computed: {
    completed: function() {
      return this.complete;
    },
    enableLog: function() {
      return this.completed && !this.finished;
    }
  },
  props: {
    configData: Object,
    complete: Boolean
  },
  methods: {
    stepComplete() {
      this.finished = true;
      const data = { ...this.configData, identityPath: this.identityPath };
      this.$emit('step-complete', data);
    },
    validate() {
      this.$refs.form.validate();
      if (this.valid) {
        this.stepComplete();
      }
    }
  }
};
</script>

<style lang="scss" scoped></style>
