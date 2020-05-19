<template>
  <div class="config-modal">
    <v-dialog v-model="open" max-width="600">
      <template v-slot:activator="{ on }">
        <div class="config-modal-activator">
          <v-btn color="primary" v-on="on" outlined>Edit</v-btn>
        </div>
      </template>
      <v-form v-model="valid" ref="form" @submit.prevent="validate()">
        <v-card>
          <v-card-title class="headline">Storage Directory</v-card-title>
          <v-card-text class="mt-4">
            <div class="form-control">
              <label>Storage Directory</label>
              <v-text-field
                required
                outlined
                single-line
                v-model="value"
                :rules="rules"
                placeholder="/path/to/storage/directory"
              ></v-text-field>
            </div>
          </v-card-text>
          <v-card-actions>
            <v-btn color="primary" @click="validate()">Save</v-btn>
            <v-btn color="primary" outlined @click="cancel()">Cancel</v-btn>
            <v-btn color="primary" text outlined @click="reset()">Reset</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
  </div>
</template>

<script>
import { storageDirectoryRules } from '@/lib/validationRules';

export default {
  name: 'StorageDirectoryDialog',
  components: {},
  data() {
    return {
      open: false,
      value: this.data.storageDirectory,
      valid: false,
      rules: storageDirectoryRules
    };
  },
  props: {
    data: Object,
    onSave: Function
  },
  watch: {
    open: {
      handler(value) {
        if (value && this.$refs.form) {
          this.$refs.form.resetValidation();
          this.value = this.data.storageDirectory;
        }
      }
    }
  },
  methods: {
    validate() {
      this.$refs.form.validate();
      if (this.valid) {
        this.save();
      }
    },
    reset() {
      this.$refs.form.resetValidation();
      this.value = this.data.storageDirectory;
    },
    save() {
      this.onSave({ ...this.data, storageDirectory: this.value });
      this.open = false;
    },
    cancel() {
      this.open = false;
    }
  }
};
</script>
