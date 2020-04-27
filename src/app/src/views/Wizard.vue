<template>
  <v-container fluid class="fill-height justify-center fill-width">
    <template>
      <v-tabs-items v-model="tabStep" class="tab-items">
        <v-tab-item key="0" class="tab-item">
          <div class="text-center">
            <v-img
              alt="Storj Logo"
              class="shrink logo"
              contain
              src="../assets/emblem.svg"
              transition="scale-transition"
            />
            <h1 class="mt-0 mb-2">Welcome to Storj!</h1>
            <p class="mb-6">
              Monetize your excess capacity on the Storj Network
            </p>
            <v-btn color="primary" large v-on:click="step++" :disabled="loading"
              >Start</v-btn
            >
          </div>
        </v-tab-item>

        <v-tab-item key="1" class="tab-item">
          <div>
            <v-icon class="back-icon" x-large v-on:click="goBack()"
              >mdi-arrow-left</v-icon
            >
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

          <v-stepper class="stepper" v-model="step" alt-labels>
            <div class="stepper-content-wrapper">
              <v-stepper-content class="stepper-content" step="1">
                <div class="stepper-content-inner">
                  <h1>Connect your Email Address</h1>
                  <p>
                    In order to recieve and hold your STORJ token payouts, you
                    need an ERC-20 compatible wallet address
                  </p>

                  <div class="form-control">
                    <label>Email Address</label>
                    <v-text-field
                      v-model="formData.email"
                      :rules="emailRules"
                      label="Email Address"
                      placeholder="mail@default.com"
                      required
                      outlined
                      single-line
                    ></v-text-field>
                  </div>

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
                </div>
              </v-stepper-content>

              <v-stepper-content class="stepper-content" step="2">
                <div class="stepper-content-inner">
                  <h1>Connect your Ethereum Wallet Address</h1>

                  <p>
                    In order to recieve and hold your STORJ token payouts, you
                    need an ERC-20 compatible wallet address
                  </p>

                  <div class="form-control">
                    <label>ETH Wallet Address</label>
                    <v-text-field
                      v-model="formData.address"
                      :rules="addressRules"
                      label="ETH Wallet Address"
                      placeholder="Enter ETH Wallet Address"
                      required
                      outlined
                      single-line
                    ></v-text-field>
                  </div>

                  <v-btn color="primary" large v-on:click="step++"
                    >Continue</v-btn
                  >
                </div>
              </v-stepper-content>

              <v-stepper-content class="stepper-content" step="3">
                <div class="stepper-content-inner">
                  <h1>Set Your Storage Allocation</h1>

                  <p>
                    How much disk space you want to allocate to the Storj
                    network
                  </p>

                  <div class="form-control">
                    <label>Storage Allocation</label>
                    <v-text-field
                      v-model="formData.storage"
                      label="Storage Allocation"
                      placeholder="1000"
                      required
                      outlined
                      single-line
                    ></v-text-field>
                  </div>

                  <v-btn color="primary" large v-on:click="step++"
                    >Continue</v-btn
                  >
                </div>
              </v-stepper-content>

              <v-stepper-content class="stepper-content" step="4">
                <div class="stepper-content-inner">
                  <h1>Set Storage Directory</h1>

                  <p>
                    The local directory where you want files to be stored on
                    your hard drive for the network
                  </p>

                  <div class="form-control">
                    <label>Storage Directory</label>
                    <v-text-field
                      v-model="formData.directory"
                      label="Storage Directory"
                      placeholder="1000"
                      required
                      outlined
                      single-line
                    ></v-text-field>
                  </div>

                  <v-btn color="primary" large v-on:click="step++"
                    >Continue</v-btn
                  >
                </div>
              </v-stepper-content>

              <v-stepper-content class="stepper-content" step="5">
                <div class="stepper-content-inner">
                  <h1>Configure Your External Port Forwarding</h1>

                  <p>
                    How a storage node communicates with others on the Storj
                    network, even though it is behind a router. You need a
                    dynamic DNS service to ensure your storage node is connected
                  </p>

                  <div class="form-control">
                    <label>Host Address</label>
                    <v-text-field
                      v-model="formData.host"
                      label="Host Address"
                      placeholder="hostname.ddns.net:28967"
                      required
                      outlined
                      single-line
                    ></v-text-field>
                  </div>

                  <v-btn color="primary" large v-on:click="step++"
                    >Continue</v-btn
                  >
                </div>
              </v-stepper-content>

              <v-stepper-content class="stepper-content" step="6">
                <v-tabs-items
                  v-model="identityStep"
                  class="stepper-content-inner tab-items"
                >
                  <v-tab-item key="0">
                    <h1>Setup Your Identity Path</h1>

                    <p>
                      Every node is required to have a unique identifier on the
                      network. If you haven't already, get an authorization
                      token. Please get the authorization token and create
                      identity on host machine other than NAS
                    </p>

                    <div class="form-control">
                      <label>Identity Path</label>
                      <v-text-field
                        v-model="formData.identity"
                        label="Identity Path"
                        placeholder="/path/to/identity"
                        required
                        outlined
                        single-line
                      ></v-text-field>
                    </div>

                    <v-btn
                      class="mr-2"
                      color="primary"
                      outlined
                      large
                      v-on:click="identityStep++"
                      >I don't have an identity</v-btn
                    >
                    <v-btn
                      class="ml-2"
                      color="primary"
                      large
                      v-on:click="step++"
                      >Finish</v-btn
                    >
                  </v-tab-item>

                  <v-tab-item key="1">
                    <h1>Generate Your Identity</h1>

                    <p>
                      Every node is required to have a unique identifier on the
                      network. If you haven't already, get an authorization
                      token. Please get the authorization token and create
                      identity on host machine other than NAS
                    </p>

                    <div class="form-control">
                      <label>Authorization Token</label>
                      <v-text-field
                        v-model="formData.authToken"
                        label="Authorization Token"
                        placeholder="user@email.com;XXXXX"
                        required
                        outlined
                        single-line
                      ></v-text-field>
                    </div>

                    <v-btn
                      class="ml-2"
                      color="primary"
                      large
                      v-on:click="identityStep++"
                      >Generate Identity</v-btn
                    >

                    <div class="info-block">
                      <v-icon color="primary" large>mdi-information</v-icon>
                      <p>
                        Creating identity can take several hours or even days,
                        depending on your machines processing power.
                      </p>
                    </div>
                  </v-tab-item>

                  <v-tab-item key="2">
                    <h1>Identity Generation Started</h1>

                    <p>
                      Creating identity can take several hours or even days,
                      depending on your machines processing power &amp;
                      probability. You will be able to track your progress after
                      configuring the rest
                    </p>

                    <div
                      style="border: 1px solid #ccc; padding: 16px; font-family: monospace; max-width: 600px; text-align: left; margin: 0 auto 2rem;"
                    >
                      Running on version v0.35.3<br /><br />
                      Generating key with a minimum a difficulty of 36<br />
                      Generated 89000 keys;<br /><br />
                      Best difficulty so far: 27 ...
                    </div>

                    <v-btn
                      class="ml-2"
                      color="primary"
                      large
                      v-on:click="identityStep = 0"
                      >Finish</v-btn
                    >
                  </v-tab-item>
                </v-tabs-items>
              </v-stepper-content>

              <v-stepper-content class="stepper-content" step="7">
                <div class="stepper-content-inner">
                  <h1>Congratulations!</h1>

                  <p>You finished the quest and ready to go</p>

                  <v-btn color="primary" large v-on:click="step = 0"
                    >Finish</v-btn
                  >
                </div>
              </v-stepper-content>
            </div>

            <v-stepper-header class="stepper-header">
              <template v-for="(item, index) in steps">
                <v-stepper-step
                  :key="`${index + 1}-step`"
                  :step="index + 1"
                  :complete="step > index + 1"
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
import axios from "axios";
export default {
  name: "Wizard",
  components: {},
  data: () => ({
    formData: {
      email: null,
      address: null,
      authToken: null,
      storage: 1000,
      directory: null,
      host: null,
      identity: null,
    },
    identityStep: 0,
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
    configData: null,
    loading: false,
    error: null,
  }),
  computed: {
    tabStep: (d) => (d.step > 0 ? 1 : 0),
  },
  methods: {
    getConfigData() {
      this.error = this.configData = null;
      this.loading = true;
      axios
        .get("/api.php", { params: { action: "config" } })
        .then(({ data }) => {
          this.configData = data.data;
          const {
            Email,
            Wallet,
            Allocation,
            Directory,
            Port,
            Identity,
          } = data.data;
          this.formData = {
            email: Email,
            address: Wallet,
            storage: Allocation,
            directory: Directory,
            host: Port,
            identity: Identity,
          };
        })
        .catch((reason) => {
          console.error(reason);
          this.error = reason;
        })
        .finally(() => (this.loading = false));
    },
    updateIdentityStep() {
      this.identityStep = 0;
    },
    goBack() {
      if (this.step === 6) {
        switch (this.identityStep) {
          case 0:
            this.step--;
            break;
          case 2:
            this.identityStep = 0;
            break;
          default:
            this.identityStep--;
        }
      } else {
        this.step--;
      }
    },
  },
  created() {
    this.getConfigData();
  },
  watch: {
    $route: "getConfigData",
    step: "updateIdentityStep",
  },
};
</script>

<style lang="scss" scoped>
.logo {
  height: 75px;
  margin-bottom: 80px;
}

.v-btn {
  font-weight: bold !important;
  letter-spacing: 0 !important;
  min-width: 200px !important;
  text-indent: 0 !important;
  text-decoration-color: none !important;
  text-decoration-style: none !important;
  text-decoration-line: none !important;
  -webkit-font-smoothing: none !important;
  &.v-size--large {
    font-size: 1rem !important;
    height: 48px !important;
  }
}

h1 {
  margin-bottom: 1rem;
}

p {
  font-size: 18px;
}

.field-label {
  display: block;
}
.field-input {
  display: block;
}

.back-icon {
  position: absolute;
  top: 0;
  left: 0;
}

.form-control {
  max-width: 500px;
  margin: 0 auto 1rem;
  text-align: left;
  label {
    display: block;
    margin-bottom: 0.5rem;
  }
  .v-input {
    input {
      background: white;
    }
  }
}

.v-stepper__step {
  flex-basis: 225px !important;
}

.divider {
  &.complete {
    border-color: #2683ff !important;
    border-width: thin !important;
  }
  margin: 35px -100px 0 !important;
}

.stepper {
  border: none;
  box-shadow: none;
  height: 100%;
  width: 100%;
  display: flex;
  flex-flow: column nowrap;
}

.stepper-content-wrapper {
  flex-grow: 1;
  display: flex;
  align-items: flex-start;
  justify-content: center;
}

.stepper-content {
  padding: 0;
  flex-grow: 1;
  height: 100%;
  width: 100%;
}

.stepper-content-inner {
  max-width: 800px;
  margin: 0 auto;
  text-align: center;
}

.stepper-header {
  border: none;
  box-shadow: none;
}

.tab-items {
  background: transparent !important;
  height: 100%;
  width: 100%;
  .tab-item {
    background: transparent !important;
    height: 100%;
    width: 100%;
    display: flex;
    flex-flow: column nowrap;
    justify-content: center;
    align-items: center;
  }
}

.v-stepper {
  background: transparent !important;
}

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
