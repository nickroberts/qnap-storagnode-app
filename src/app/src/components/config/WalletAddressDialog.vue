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
          <v-card-title class="headline">Ethereum Wallet Address</v-card-title>
          <v-card-text class="mt-4">
            <div class="form-control">
              <label>Ethereum Wallet Address</label>
              <v-text-field
                required
                outlined
                single-line
                v-model="value"
                :rules="rules"
                placeholder="0x0000000000000000000000000000000000000000"
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
import { walletAddressRules } from '@/lib/validationRules';

export default {
  name: 'WalletAddressDialog',
  components: {},
  data() {
    return {
      open: false,
      value: this.data.walletAddress,
      valid: false,
      rules: walletAddressRules
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
          this.value = this.data.emailAddress;
        }
      }
    }
  },
  methods: {
    validate() {
      this.$refs.form.resetValidation();
      if (this.valid) {
        this.save();
      }
    },
    reset() {
      this.$refs.form.reset();
      this.value = this.data.walletAddress;
    },
    save() {
      this.onSave({ ...this.data, walletAddress: this.value });
      this.open = false;
    },
    cancel() {
      this.open = false;
    }
  }
};
</script>
