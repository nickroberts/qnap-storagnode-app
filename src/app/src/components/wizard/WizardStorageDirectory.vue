<template>
  <v-form v-model="valid" ref="form" @submit.prevent="validate()">
    <h1>Set Storage Directory</h1>

    <p>
      The local directory where you want files to be stored on your hard drive for the network
    </p>

    <div class="form-control">
      <label>Storage Directory</label>
      <v-text-field
        v-model="storageDirectory"
        :rules="storageDirectoryRules"
        label="Storage Directory"
        placeholder="/path/to/storage/directory"
        required
        outlined
        single-line
      ></v-text-field>
    </div>

    <v-btn color="primary" x-large @click="validate()" :disabled="!valid">Continue</v-btn>
  </v-form>
</template>

<script>
import { storageDirectoryRules } from '@/lib/validationRules';

export default {
  name: 'WizardStorageDirectory',
  components: {},
  data: function() {
    return {
      storageDirectory: this.configData.storageDirectory,
      storageDirectoryRules,
      valid: false
    };
  },
  props: {
    configData: Object
  },
  methods: {
    complete() {
      const data = { ...this.configData, storageDirectory: this.storageDirectory };
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
