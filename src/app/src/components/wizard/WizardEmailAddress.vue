<template>
  <v-form v-model="valid" ref="form" @submit.prevent="validate()">
    <h1>Connect your Email Address</h1>
    <p>
      Join thousands of Node Operators around the world by getting Node status updates from Storj Labs.
    </p>

    <div class="form-control">
      <label>Email Address</label>
      <v-text-field
        v-model="emailAddress"
        :rules="emailAddressRules"
        label="Email Address"
        placeholder="mail@default.com"
        required
        outlined
        single-line
      ></v-text-field>
    </div>

    <v-btn class="mr-2" color="primary" outlined x-large @click="$emit('step-complete')">Skip this step</v-btn>
    <v-btn class="ml-2" color="primary" x-large @click="validate()" :disabled="!valid">Continue</v-btn>
  </v-form>
</template>

<script>
import { emailAddressRules } from '@/lib/validationRules';

export default {
  name: 'WizardEmailAddress',
  components: {},
  data: function() {
    return {
      emailAddress: this.configData.emailAddress,
      emailAddressRules,
      valid: false
    };
  },
  props: {
    configData: Object
  },
  methods: {
    complete() {
      const data = { ...this.configData, emailAddress: this.emailAddress };
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
