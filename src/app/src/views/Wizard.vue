<template>
  <v-container fluid class="fill-height justify-center fill-width">
    <template>
      <v-tabs-items v-model="tabStep" class="tab-items">
        <v-tab-item key="0" class="tab-item">
          <WizardWelcome v-on:step-complete="stepComplete" />
        </v-tab-item>

        <v-tab-item key="1" class="tab-item">
          <div>
            <v-icon class="back-icon" x-large v-on:click="goBack" v-if="!complete">arrow_back</v-icon>
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
                <WizardEmailAddress
                  class="stepper-content-inner"
                  :config-data="configData"
                  v-on:step-complete="stepComplete"
                />
              </v-stepper-content>

              <v-stepper-content class="stepper-content" step="2">
                <WizardWalletAddress
                  class="stepper-content-inner"
                  :config-data="configData"
                  v-on:step-complete="stepComplete"
                />
              </v-stepper-content>

              <v-stepper-content class="stepper-content" step="3">
                <WizardStorageAllocation
                  class="stepper-content-inner"
                  :config-data="configData"
                  v-on:step-complete="stepComplete"
                />
              </v-stepper-content>

              <v-stepper-content class="stepper-content" step="4">
                <WizardStorageDirectory
                  class="stepper-content-inner"
                  :config-data="configData"
                  v-on:step-complete="stepComplete"
                />
              </v-stepper-content>

              <v-stepper-content class="stepper-content" step="5">
                <WizardHostname
                  class="stepper-content-inner"
                  :config-data="configData"
                  v-on:step-complete="stepComplete"
                />
              </v-stepper-content>

              <v-stepper-content class="stepper-content" step="6">
                <v-tabs-items v-model="identityStep" class="stepper-content-inner tab-items">
                  <v-tab-item key="0">
                    <WizardIdentityPath
                      class="stepper-content-inner"
                      :config-data="configData"
                      :complete="complete"
                      v-on:step-complete="stepComplete"
                      v-on:no-identity="noIdentity"
                    />
                  </v-tab-item>

                  <v-tab-item key="1">
                    <WizardAuthorizationToken
                      class="stepper-content-inner"
                      :config-data="configData"
                      v-on:step-complete="startIdentityGeneration"
                    />
                  </v-tab-item>

                  <v-tab-item key="2">
                    <WizardIdentityGenerationStarted class="stepper-content-inner" v-on:step-complete="stepComplete" />
                  </v-tab-item>
                </v-tabs-items>
              </v-stepper-content>

              <v-stepper-content class="stepper-content" step="7">
                <WizardComplete class="stepper-content-inner" v-on:step-complete="finish" />
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
import * as api from '@/lib/api';
import WizardWelcome from '@/components/wizard/WizardWelcome.vue';
import WizardEmailAddress from '@/components/wizard/WizardEmailAddress.vue';
import WizardWalletAddress from '@/components/wizard/WizardWalletAddress.vue';
import WizardStorageAllocation from '@/components/wizard/WizardStorageAllocation.vue';
import WizardStorageDirectory from '@/components/wizard/WizardStorageDirectory.vue';
import WizardHostname from '@/components/wizard/WizardHostname.vue';
import WizardIdentityPath from '@/components/wizard/WizardIdentityPath.vue';
import WizardAuthorizationToken from '@/components/wizard/WizardAuthorizationToken.vue';
import WizardIdentityGenerationStarted from '@/components/wizard/WizardIdentityGenerationStarted.vue';
import WizardComplete from '@/components/wizard/WizardComplete.vue';

export default {
  name: 'Wizard',
  components: {
    WizardWelcome,
    WizardEmailAddress,
    WizardWalletAddress,
    WizardStorageAllocation,
    WizardStorageDirectory,
    WizardHostname,
    WizardIdentityPath,
    WizardAuthorizationToken,
    WizardIdentityGenerationStarted,
    WizardComplete
  },
  data: () => ({
    complete: false,
    identityStep: 0,
    step: 0,
    steps: [
      { title: 'Email Address' },
      { title: 'ETH Wallet Address' },
      { title: 'Storage Allocation' },
      { title: 'Storage Directory' },
      { title: 'Port Forwarding' },
      { title: 'Identity' }
    ]
  }),
  computed: {
    tabStep: d => (d.step > 0 ? 1 : 0)
  },
  methods: {
    async getConfigData() {
      const configData = await api.getConfig();
      this.configData = configData;
    },
    updateIdentityStep() {
      if (this.step !== 6) {
        this.identityStep = 0;
      }
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
    finish() {
      this.complete = true;
      this.$router.push({ path: '/' });
    },
    noIdentity() {
      this.identityStep++;
    },
    async saveConfig(data) {
      const configData = await api.saveConfig(data);
      this.configData = configData;
    },
    async startIdentityGeneration(authToken) {
      this.complete = true;
      console.log('make api call to generate identity with authToken: ' + authToken);
      await this.saveConfig({ ...this.configData, identityPath: '/some/auto/generated/path' });
      this.identityStep++;
    },
    async stepComplete(data) {
      if (data) {
        await this.saveConfig(data);
      }
      if (this.step === 6 && this.identityStep === 2) {
        this.identityStep = 0;
      } else {
        this.step++;
      }
    }
  },
  created() {
    this.getConfigData();
  },
  watch: {
    $route: 'getConfigData',
    step: 'updateIdentityStep'
  }
};
</script>

<style lang="scss" scoped>
.logo {
  height: 75px;
  margin-bottom: 80px;
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
</style>
