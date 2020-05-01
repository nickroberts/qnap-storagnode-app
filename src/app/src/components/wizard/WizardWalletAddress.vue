<template>
  <v-form v-model="valid" ref="form" @submit.prevent="validate()">
    <h1>Connect your Ethereum Wallet Address</h1>

    <p>
      In order to recieve and hold your STORJ token payouts, you need an ERC-20 compatible wallet address
    </p>

    <div class="form-control">
      <label>ETH Wallet Address</label>
      <v-text-field
        v-model="walletAddress"
        :rules="walletAddressRules"
        label="ETH Wallet Address"
        placeholder="0x0000000000000000000000000000000000000000"
        required
        outlined
        single-line
      ></v-text-field>
    </div>

    <v-btn color="primary" x-large @click="validate()" :disabled="!valid">Continue</v-btn>
  </v-form>
</template>

<script>
import { walletAddressRules } from '@/lib/validationRules';

export default {
  name: 'WizardWalletAddress',
  components: {},
  data: function() {
    return {
      walletAddress: this.configData.walletAddress,
      walletAddressRules,
      valid: false
    };
  },
  props: {
    configData: Object
  },
  methods: {
    complete() {
      const data = { ...this.configData, walletAddress: this.walletAddress };
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
