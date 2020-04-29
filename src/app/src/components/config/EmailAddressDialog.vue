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
          <v-card-title class="headline">Email Address</v-card-title>
          <v-card-text class="mt-4">
            <div class="form-control">
              <label>Email Address</label>
              <v-text-field
                required
                outlined
                single-line
                v-model="value"
                :rules="rules"
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
import { emailAddressRules } from "@/lib/validationRules";

export default {
  name: "EmailAddressDialog",
  components: {},
  data() {
    return {
      open: false,
      value: this.data.emailAddress,
      valid: false,
      rules: emailAddressRules,
    };
  },
  props: {
    data: Object,
    onSave: Function,
  },
  watch: {
    open: {
      handler(value) {
        if (value && this.$refs.form) {
          this.$refs.form.resetValidation();
          this.value = this.data.emailAddress;
        }
      },
    },
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
      this.value = this.data.emailAddress;
    },
    save() {
      this.onSave({ ...this.data, emailAddress: this.value });
      this.open = false;
    },
    cancel() {
      this.open = false;
    },
  },
};
</script>
