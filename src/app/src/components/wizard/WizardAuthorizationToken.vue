<template>
  <v-form v-model="valid" ref="form" @submit.prevent="validate()">
    <h1>Generate Your Identity</h1>

    <p>
      Every node is required to have a unique identifier on the network. If you haven't already, get an authorization
      token. Please get the authorization token and create identity on host machine other than NAS
    </p>

    <div class="form-control">
      <label>Authorization Token</label>
      <v-text-field
        v-model="authorizationToken"
        :rules="authorizationTokenRules"
        label="Authorization Token"
        placeholder="user@email.com;XXXXX"
        required
        outlined
        single-line
      ></v-text-field>
    </div>

    <v-btn color="primary" x-large @click="validate()" :disabled="!valid">Generate Identity</v-btn>

    <div class="info-block">
      <v-icon color="primary" large>info</v-icon>
      <p>
        Creating identity can take several hours or even days, depending on your machines processing power.
      </p>
    </div>
  </v-form>
</template>

<script>
import { authorizationTokenRules } from '@/lib/validationRules';

export default {
  name: 'WizardAuthorizationToken',
  components: {},
  data: function() {
    return {
      authorizationToken: this.configData.authorizationToken,
      authorizationTokenRules,
      valid: false
    };
  },
  props: {
    configData: Object
  },
  methods: {
    complete() {
      console.log('complete', this.authorizationToken);
      this.$emit('step-complete', this.authorizationToken);
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

<style lang="scss" scoped>
.info-block {
  max-width: 700px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 2rem auto 0;
  p {
    font-size: 16px;
    margin-bottom: 0;
    margin-left: 24px;
    text-align: left;
  }
}
</style>
