<template>
  <v-container fluid class="fill-height justify-center" style="width: 100%;">
    <template>
      <v-tabs-items v-model="tabStep" class="tab-items">
        <v-tab-item key="0" class="tab-item">
          <div class="text-center">
            <v-img
              alt="Storj Logo"
              class="shrink logo mb-6"
              contain
              src="../assets/emblem.svg"
              transition="scale-transition"
            />
            <h1 class="mt-0 mb-2">Welcome to Storj!</h1>
            <p class="mb-6">
              Monetize your excess capacity on the Storj Network
            </p>
            <v-btn color="primary" large v-on:click="step++">Start</v-btn>
          </div>
        </v-tab-item>

        <v-tab-item key="1" class="tab-item">
          <div class="d-flex align-center justifty-content-space-evenly">
            <v-icon x-large v-on:click="step--">mdi-arrow-left</v-icon>
            <v-img
              alt="Storj Logo"
              class="shrink logo"
              contain
              src="../assets/emblem.svg"
              transition="scale-transition"
              height="175"
              width="175"
            />
          </div>

          <v-spacer></v-spacer>

          <v-stepper class="stepper" v-model="step" alt-labels>
            <v-stepper-content class="stepper-content" step="1">
              <h1>Connect your Email Address</h1>
              <p>
                Descriptino for the email address screen goes here (it was the
                same as the wallet screen).
              </p>

              <v-text-field
                v-model="email"
                :rules="emailRules"
                label="Email Address"
                placeholder="mail@default.com"
                required
              ></v-text-field>

              <v-btn
                class="mr-2"
                color="primary"
                outlined
                large
                v-on:click="step++"
                >Skip this step</v-btn
              >
              <v-btn class="ml-2" color="primary" large v-on:click="step++"
                >Continue</v-btn
              >
            </v-stepper-content>

            <v-stepper-content class="stepper-content" step="2">
              <h1>Connect your Ethereum Wallet Address</h1>

              <p>
                In order to recieve and hold your STORJ token payouts, you need
                an ERC-20 compatible wallet address
              </p>

              <v-text-field
                v-model="address"
                :rules="addressRules"
                label="ETH Wallet Address"
                placeholder="Enter ETH Wallet Address"
                required
              ></v-text-field>

              <v-btn color="primary" large v-on:click="step++">Continue</v-btn>
            </v-stepper-content>

            <v-stepper-content class="stepper-content" step="3">
              <h1>Set Your Storage Allocation</h1>

              <p>
                How much disk space you want to allocate to the Storj network
              </p>

              <v-text-field
                v-model="storage"
                label="Storage Allocation"
                placeholder="1000"
                required
              ></v-text-field>

              <v-btn color="primary" large v-on:click="step++">Continue</v-btn>
            </v-stepper-content>

            <v-stepper-content class="stepper-content" step="4">
              <h1>Configure Your External Port Forwarding</h1>

              <p>
                How a storage node communicates with others on the Storj
                network, even though it is behind a router. You need a dynamic
                DNS service to ensure your storage node is connected
              </p>

              <v-text-field
                v-model="host"
                label="Host Address"
                placeholder="hostname.ddns.net:28967"
                required
              ></v-text-field>

              <v-btn color="primary" large v-on:click="step++">Continue</v-btn>
            </v-stepper-content>

            <v-stepper-content class="stepper-content" step="5">
              <h1>Setup Your Identity Path</h1>

              <p>
                Every node is required to have a unique identifier on the
                network. If you haven't already, get an authorization token.
                Please get the authorization token and create identity on host
                machine other than NAS
              </p>

              <v-text-field
                v-model="identity"
                label="Identity Path"
                placeholder="/path/to/identity"
                required
              ></v-text-field>

              <v-btn class="mr-2" color="primary" outlined large
                >I don't have an identity</v-btn
              >
              <v-btn class="ml-2" color="primary" large v-on:click="step++"
                >Finish</v-btn
              >
            </v-stepper-content>

            <v-stepper-content class="stepper-content" step="6">
              <h1>Congratulations!</h1>

              <p>You finished the quest and ready to go</p>

              <v-btn color="primary" large v-on:click="step = 0">Finish</v-btn>
            </v-stepper-content>

            <v-spacer></v-spacer>

            <v-stepper-header class="stepper-header" v-if="step > 0">
              <template v-for="(item, index) in steps">
                <v-stepper-step
                  :key="`${index + 1}-step`"
                  :step="index + 1"
                  :complete="step > index"
                  :editable="step > index + 1"
                >
                  <div class="text-center">
                    {{ item.title }}
                  </div>
                </v-stepper-step>
                <v-divider
                  v-if="index + 1 !== steps.length"
                  class="divider"
                  v-bind:class="{ complete: step > index + 1 }"
                  :key="index + 1"
                ></v-divider>
              </template>
            </v-stepper-header>
          </v-stepper>
        </v-tab-item>
      </v-tabs-items>
    </template>
  </v-container>
</template>

<script>
export default {
  name: "Wizard",
  components: {},
  data: () => ({
    email: "",
    emailValid: false,
    address: "",
    storage: 1000,
    host: "",
    identity: "",
    started: false,
    step: 0,
    steps: [
      { title: "Email Address" },
      { title: "ETH Wallet Address" },
      { title: "Storage Allocation" },
      { title: "Storage Directory" },
      { title: "Port Forwarding" },
      { title: "Identity" },
    ],
    emailRules: [
      (v) => !!v || "E-mail is required",
      (v) => /.+@.+/.test(v) || "E-mail must be valid",
    ],
    addressRules: [
      // 0x0123456789012345678901234567890123456789
      (v) => /^0x[a-fA-F0-9]{40}$/g.test(v) || "Address must be valid",
    ],
  }),
  computed: {
    tabStep: (d) => (d.step > 0 ? 1 : 0),
  },
  methods: {
    validate() {
      console.log("validate");
      this.$refs.emailForm.validate();
    },
    handleSubmit(e) {
      e.preventDefault();
      console.log("submit");
      this.step++;
      return false;
    },
  },
};
</script>

<style lang="scss" scoped>
@import "~vuetify/src/styles/styles.sass";

.logo {
  height: 100px;
}

.divider {
  &.complete {
    border-color: #2683ff !important;
  }
  margin: 35px -76px 0 !important;
}

.stepper {
  border: none;
  box-shadow: none;
  height: 100%;
  width: 100%;
  display: flex;
  flex-flow: column nowrap;
}

.stepper-content {
  padding: 0;
}

.stepper-header {
  border: none;
  box-shadow: none;
}

.tab-items {
  height: 100%;
  width: 100%;
  .tab-item {
    height: 100%;
    width: 100%;
    display: flex;
    flex-flow: column nowrap;
    justify-content: center;
    align-items: center;
  }
}
</style>
