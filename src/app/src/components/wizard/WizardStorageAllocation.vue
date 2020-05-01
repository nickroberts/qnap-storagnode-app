<template>
  <v-form v-model="valid" ref="form" @submit.prevent="validate()">
    <h1>Set Your Storage Allocation</h1>

    <p>
      How much disk space you want to allocate to the Storj network
    </p>

    <div class="form-control">
      <label>Storage Allocation</label>
      <v-text-field
        v-model="storageAllocation"
        :rules="storageAllocationRules"
        label="Storage Allocation"
        placeholder="1000"
        required
        outlined
        single-line
        suffix="GB"
      ></v-text-field>
    </div>

    <v-btn color="primary" x-large @click="validate()" :disabled="!valid">Continue</v-btn>
  </v-form>
</template>

<script>
import { storageAllocationRules } from '@/lib/validationRules';

export default {
  name: 'WizardStorageAllocation',
  components: {},
  data: function() {
    return {
      storageAllocation: this.configData.storageAllocation,
      storageAllocationRules,
      valid: false
    };
  },
  props: {
    configData: Object
  },
  methods: {
    complete() {
      const data = { ...this.configData, storageAllocation: this.storageAllocation };
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
