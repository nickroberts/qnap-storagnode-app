<template>
  <div class="config-modal">
    <v-dialog v-model="open" max-width="600">
      <template v-slot:activator="{ on }">
        <div class="config-modal-activator">
          <v-btn color="primary" v-on="on" outlined>Edit</v-btn>
          <v-btn color="primary" class="ml-2" text>
            <v-icon left dark>cloud_upload</v-icon>
            Upload
          </v-btn>
        </div>
      </template>
      <v-form v-model="valid" ref="form" @submit.prevent="validate()">
        <v-card>
          <v-card-title class="headline">Identity Path</v-card-title>
          <v-card-text class="mt-4">
            <div class="form-control">
              <label>Authorization Token</label>
              <v-text-field
                required
                outlined
                single-line
                v-model="authorizationToken"
                :rules="identityPathRules"
              ></v-text-field>
            </div>

            <div class="form-control mt-2">
              <label>Identity Path</label>
              <v-text-field
                required
                outlined
                single-line
                v-model="identityPath"
                :rules="authorizationTokenRules"
              ></v-text-field>
            </div>
          </v-card-text>
          <v-card-actions>
            <v-btn color="primary" @click="validate()">Save</v-btn>
            <v-btn color="secondary" @click="generate()"
              >Generate Identity</v-btn
            >
            <v-btn color="primary" outlined @click="cancel()">Cancel</v-btn>
            <v-btn color="primary" text outlined @click="reset()">Reset</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
  </div>
</template>

<script>
import {
  identityPathRules,
  authorizationTokenRules,
} from "@/lib/validationRules";

export default {
  name: "IdentityPathDialog",
  components: {},
  data() {
    return {
      open: false,
      authorizationToken: this.data.authorizationToken,
      identityPath: this.data.identityPath,
      valid: false,
      identityPathRules: identityPathRules,
      authorizationTokenRules: authorizationTokenRules,
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
          this.authorizationToken = this.data.authorizationToken;
          this.identityPath = this.data.identityPath;
        }
      },
    },
  },
  methods: {
    generate() {
      console.log("TODO!");
    },
    validate() {
      this.$refs.form.validate();
      if (this.valid) {
        this.save();
      }
    },
    reset() {
      this.$refs.form.resetValidation();
      this.authorizationToken = this.data.authorizationToken;
      this.identityPath = this.data.identityPath;
    },
    save() {
      this.onSave({
        ...this.data,
        authorizationToken: this.authorizationToken,
        identityPath: this.identityPath,
      });
      this.open = false;
    },
    cancel() {
      this.open = false;
    },
  },
};
</script>
