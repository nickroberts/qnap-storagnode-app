<template>
  <div id="app">
    <div v-bind:class="stepClass">
      <img
        class="back"
        src="resources/img/back.png"
        v-if="step > 1"
        v-on:click="step--"
      />

      <div v-if="step === 1">
        <img class="logo" src="resources/img/logo.png" />
        <h1 class="title">Welcome to Storj!</h1>
        <p>Monetize your excess capacity on the Storj Network</p>

        <button class="start" v-on:click="step++">Start</button>
      </div>

      <div v-if="step === 2">
        <div class="profile"></div>
        <h1 class="title">Connect your Email Address</h1>

        <p class="tagline">
          In order to recieve and hold your STORJ token payouts, you need an
          ERC-20 compatible wallet address
        </p>

        <label class="email-label">Email Address</label>
        <input
          type="email"
          class="email"
          placeholder="mail@default.com"
          v-model="email"
          v-bind:class="{ invalid: !emailValid }"
        />

        <button class="skip">Skip this step</button>
        <button
          class="continue"
          v-on:click="step++"
          v-bind:disabled="!emailValid"
        >
          Continue
        </button>
      </div>

      <div v-if="step === 3">
        <div class="profile"></div>
        <h1 class="title">Connect your Ethereum Wallet Address</h1>

        <p class="tagline">
          In order to recieve and hold your STORJ token payouts, you need an
          ERC-20 compatible wallet address
        </p>

        <label class="address-label">ETH Wallet Address</label>
        <input
          type="text"
          class="address"
          placeholder="Enter ETH Wallet Address"
          v-model="address"
          v-bind:class="{ invalid: !addressValid }"
        />

        <button
          class="continue"
          v-on:click="step++"
          v-bind:disabled="!addressValid"
        >
          Continue
        </button>
      </div>

      <div v-if="step === 4">
        <div class="profile"></div>
        <h1 class="title">Set Your Storage Allocation</h1>

        <p class="tagline">
          How much disk space you want to allocate to the Storj network
        </p>

        <label class="storage-label">Storage Allocation</label>
        <input
          class="storage"
          type="number"
          min="1"
          max="1000"
          value="1000000"
          v-model="storage"
          v-bind:class="{ invalid: !storageValid }"
        />
        <span class="unit">GB</span>

        <button
          class="continue"
          v-on:click="step++"
          v-bind:disabled="!storageValid"
        >
          Continue
        </button>
      </div>

      <div v-if="step === 5">
        <div class="profile"></div>
        <h1 class="title">Set Storage Directory</h1>

        <p class="tagline">
          The local directory where you want files to be stored on your hard
          drive for the network
        </p>

        <label class="directory-label">Storage Directory</label>
        <input
          class="directory"
          type="text"
          placeholder="/path/to/folder_to_share"
          v-model="directory"
          v-bind:class="{ invalid: !directoryValid }"
        />

        <button
          class="continue"
          v-on:click="step++"
          v-bind:disabled="!directoryValid"
        >
          Continue
        </button>
      </div>

      <div v-if="step === 6">
        <div class="profile"></div>
        <h1 class="title">Configure Your External Port Forwarding</h1>

        <p class="tagline">
          How a storage node communicates with others on the Storj network, even
          though it is behind a router. You need a dynamic DNS service to ensure
          your storage node is connected
        </p>

        <label class="host-label">Host Address</label>
        <input
          class="host"
          type="text"
          placeholder="hostname.ddns.net:28967"
          v-model="host"
          v-bind:class="{ invalid: !hostValid }"
        />

        <button
          class="continue"
          v-on:click="step++"
          v-bind:disabled="!hostValid"
        >
          Continue
        </button>
      </div>

      <div v-if="step === 7">
        <div class="identity-step-1" v-if="identityStep === 1">
          <div class="profile"></div>
          <h1 class="title">Setup Your Identity Path</h1>

          <p class="tagline">
            Every node is required to have a unique identifier on the network.
            If you haven't already, get an authorization token. Please get the
            authorization token and create identity on host machine other than
            NAS
          </p>

          <label class="identity-label">Identity Path</label>
          <input
            class="identity"
            type="text"
            placeholder="/path/to/identity"
            v-model="identity"
          />

          <button class="no-identity" v-on:click="identityStep++">
            I don't have an identity
          </button>
          <button class="finish" v-on:click="step++">Finish</button>
        </div>

        <div class="identity-step-2" v-if="identityStep === 2">
          <div class="profile"></div>
          <h1 class="title">Generate Your Identity</h1>

          <p class="tagline">
            Every node is required to have a unique identifier on the network.
            If you haven't already, get an authorization token. Please get the
            authorization token and create identity on host machine other than
            NAS
          </p>

          <button class="generate" v-on:click="generateIdentity">
            Generate
          </button>

          <div class="warning-icon"><img src="resources/img/i.png" /></div>
          <p class="warning">
            Creating identity can take several hours or even days, depending on
            your machines processing power.
          </p>
        </div>

        <div class="identity-step-3" v-if="identityStep === 3">
          <div class="profile"></div>
          <h1 class="title">Identity Generation Started</h1>

          <p class="tagline">
            Creating identity can take several hours or even days, depending on
            your machines processing power & probability. You will be able to
            track your progress after configuring the rest
          </p>

          <div class="logs">{{ identityLogs }}</div>

          <button class="finish" v-on:click="step++">Finish</button>
        </div>
      </div>

      <div v-if="step === 8">
        <div class="profile"></div>
        <h1 class="title">Congratulations!</h1>

        <p class="tagline">You finished the quest and ready to go</p>

        <button class="finish" v-on:click="finish">Finish</button>
      </div>

      <div class="progress" v-if="step > 1">
        <div class="point-1" v-bind:class="{ 'point-active': step > 1 }"></div>
        <div class="label-1" v-bind:class="{ 'label-active': step > 1 }">1</div>
        <div class="text-1" v-bind:class="{ 'text-active': step === 2 }">
          Email Address
        </div>

        <div class="bar-1" v-bind:class="{ 'bar-active': step > 2 }"></div>

        <div class="point-2" v-bind:class="{ 'point-active': step > 2 }"></div>
        <div class="label-2" v-bind:class="{ 'label-active': step > 2 }">2</div>
        <div class="text-2" v-bind:class="{ 'text-active': step === 3 }">
          ETH Wallet Address
        </div>

        <div class="bar-2" v-bind:class="{ 'bar-active': step > 3 }"></div>

        <div class="point-3" v-bind:class="{ 'point-active': step > 3 }"></div>
        <div class="label-3" v-bind:class="{ 'label-active': step > 3 }">3</div>
        <div class="text-3" v-bind:class="{ 'text-active': step === 4 }">
          Storage Allocation
        </div>

        <div class="bar-3" v-bind:class="{ 'bar-active': step > 4 }"></div>

        <div class="point-4" v-bind:class="{ 'point-active': step > 4 }"></div>
        <div class="label-4" v-bind:class="{ 'label-active': step > 4 }">4</div>
        <div class="text-4" v-bind:class="{ 'text-active': step === 5 }">
          Storage Directory
        </div>

        <div class="bar-4" v-bind:class="{ 'bar-active': step > 5 }"></div>

        <div class="point-5" v-bind:class="{ 'point-active': step > 5 }"></div>
        <div class="label-5" v-bind:class="{ 'label-active': step > 5 }">5</div>
        <div class="text-5" v-bind:class="{ 'text-active': step === 6 }">
          Port Forwarding
        </div>

        <div class="bar-5" v-bind:class="{ 'bar-active': step > 6 }"></div>

        <div class="point-6" v-bind:class="{ 'point-active': step > 6 }"></div>
        <div class="label-6" v-bind:class="{ 'label-active': step > 6 }">6</div>
        <div class="text-6" v-bind:class="{ 'text-active': step === 7 }">
          Identity
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "WeeWizard",
  components: {},
  data: () => ({
    step: 1,
    identityStep: 1,
    identityLogs: "",

    email: "",
    address: "",
    storage: 1000,
    directory: "",
    host: "",
    identity: "",
  }),
  computed: {
    stepClass() {
      const obj = {};

      obj["step"] = true;
      obj[`step-${this.step}`] = true;

      return obj;
    },

    emailValid() {
      return this.email.match(
        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
      );
    },

    addressValid() {
      return this.address.match(/^0x[a-fA-F0-9]{40}$/g);
    },

    storageValid() {
      return this.storage > 0;
    },

    directoryValid() {
      return this.directory.length > 1;
    },

    hostValid() {
      const [host, port] = this.host.split(":");

      if (typeof port !== "string" || port.length === 0) {
        return false;
      }

      if (isNaN(Number(port)) === true) {
        return false;
      }

      if (!host) {
        return false;
      }

      return true;
    },
  },
  methods: {
    async generateIdentity() {
      try {
        await axios.post("identity.php", {
          createidval: true,
        });
      } catch (err) {
        alert("Failed to start identity creation. Check console for details");
        console.log(err);

        return;
      }

      this.identityStep++;

      this.updateLog();

      // setTimeout(() => this.updateLog(), 10 * 1000);
    },

    async updateLog() {
      const { data } = await axios.post("identity.php", {
        status: true,
      });

      this.log = data;
    },

    async finish() {
      const data = {
        email: this.email,
        address: this.address,
        host: this.host,
        identity: this.identity,
      };

      await axios.post("config.php", data);

      location.href = "dashboard.php";
    },
  },
};
</script>

<style lang="scss" scoped>
@import url("https://rsms.me/inter/inter.css");

body {
  /*background: #E5E5E5;*/
  font-family: "Inter";
}

#app {
  width: 1400px;
  height: 900px;
  position: relative;
  margin-left: auto;
  margin-right: auto;
}

.back {
  position: absolute;
  width: 32px;
  height: 32px;
  left: 144px;
  top: 80px;

  cursor: pointer;
}

.step {
  background: white;
  display: block;

  width: 1400px;
  height: 900px;

  font-family: "Inter";
}

.invalid {
  border: 1px solid #eb5757 !important;
}

/* Step 1 */

.step-1 .logo {
  position: absolute;
  top: 295px;
  left: 685.2px;

  width: 67.78px;
  height: 76.36px;
}

.step-1 .title {
  position: absolute;
  width: 278px;
  height: 32px;
  left: 580px;
  top: 440px;

  font-family: Inter;
  font-style: normal;
  font-weight: bold;
  font-size: 32px;
  line-height: 100%;
  /* identical to box height, or 32px */

  display: flex;
  align-items: center;
  text-align: center;

  color: #0d1826;
}

.step-1 p {
  position: absolute;
  width: 463px;
  height: 24px;
  left: 488px;
  top: 496px;

  font-family: Inter;
  font-style: normal;
  font-weight: normal;
  font-size: 18px;
  line-height: 135%;
  /* or 24px */

  display: flex;
  align-items: center;
  text-align: center;

  color: #384b65;
}

.step-1 .start {
  position: absolute;
  left: 617px;
  right: 618px;
  top: 556px;
  bottom: 296px;

  /* acnt-1 */

  background: #2683ff;
  border-radius: 6px;

  font-family: Inter;
  font-style: normal;
  font-weight: bold;
  font-size: 16px;
  line-height: 135%;

  text-align: center;

  color: #ffffff;

  width: 205px;
  height: 48px;
}

.step .profile {
  position: absolute;
  width: 176px;
  height: 176px;
  left: 632px;
  top: 50px;

  border-radius: calc(176px / 2);

  background: #dee4ed;
}

/* Step 2 */

.step-2 .title {
  position: absolute;
  width: 445px;
  height: 32px;
  left: 498px;
  top: 274px;

  font-family: Inter;
  font-style: normal;
  font-weight: bold;
  font-size: 32px;
  line-height: 100%;
  /* identical to box height, or 32px */

  display: flex;
  align-items: center;
  text-align: center;

  color: #0d1826;
}

.step-2 .tagline {
  position: absolute;
  width: 711px;
  height: 52px;
  left: 364px;
  top: 330px;

  font-family: Inter;
  font-style: normal;
  font-weight: normal;
  font-size: 18px;
  line-height: 145%;
  /* or 26px */

  text-align: center;

  color: #384b65;
}

.step-2 .email-label {
  position: absolute;
  height: 22px;
  left: 445px;
  right: 735px;
  top: 434px;

  font-family: Inter;
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 134.09%;
  /* identical to box height, or 21px */

  display: flex;
  align-items: center;

  color: #384b65;
}

.step-2 .email {
  position: absolute;
  left: 445px;
  right: 445px;
  top: 464px;
  bottom: 384px;

  background: #ffffff;
  border: 1px solid #dee4ed;
  box-sizing: border-box;
  border-radius: 6px;

  width: 550px;
  height: 52px;

  padding-left: 20px;

  font-size: 16px;
}

.step-2 .skip {
  position: absolute;
  left: 505px;
  right: 730px;
  top: 552px;
  bottom: 300px;

  border: 1px solid #2683ff;
  box-sizing: border-box;
  border-radius: 6px;

  height: 48px;
  width: 205px;

  font-family: Inter;
  font-style: normal;
  font-weight: 600;
  font-size: 16px;
  line-height: 135%;

  color: #2683ff;
}

.step-2 .continue {
  position: absolute;
  left: 730px;
  right: 505px;
  top: 552px;
  bottom: 300px;

  /* acnt-1 */

  background: #2683ff;
  border-radius: 6px;

  height: 48px;
  width: 205px;

  font-family: Inter;
  font-style: normal;
  font-weight: 600;
  font-size: 16px;
  line-height: 135%;

  color: #ffffff;
}

/* Step 3 */

.step-3 .title {
  position: absolute;
  width: 618px;
  height: 32px;
  left: 411px;
  top: 274px;

  font-family: Inter;
  font-style: normal;
  font-weight: bold;
  font-size: 32px;
  line-height: 100%;
  /* identical to box height, or 32px */

  display: flex;
  align-items: center;
  text-align: center;

  color: #0d1826;
}

.step-3 .tagline {
  position: absolute;
  width: 711px;
  height: 52px;
  left: 364px;
  top: 330px;

  font-family: Inter;
  font-style: normal;
  font-weight: normal;
  font-size: 18px;
  line-height: 145%;
  /* or 26px */

  text-align: center;

  color: #384b65;
}

.step-3 .address-label {
  position: absolute;
  height: 22px;
  left: 445px;
  right: 735px;
  top: 434px;

  font-family: Inter;
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 134.09%;
  /* identical to box height, or 21px */

  display: flex;
  align-items: center;

  color: #384b65;
}

.step-3 .address {
  position: absolute;
  left: 445px;
  right: 445px;
  top: 464px;
  bottom: 384px;

  background: #ffffff;
  border: 1px solid #dee4ed;
  box-sizing: border-box;
  border-radius: 6px;

  width: 550px;
  height: 52px;

  padding-left: 20px;

  font-size: 16px;
}

.step-3 .continue {
  position: absolute;
  left: 618px;
  right: 617px;
  top: 552px;
  bottom: 300px;

  /* acnt-1 */

  background: #2683ff;
  border-radius: 6px;

  width: 205px;
  height: 48px;

  font-family: Inter;
  font-style: normal;
  font-weight: bold;
  font-size: 16px;
  line-height: 135%;
  /* identical to box height, or 22px */

  align-items: center;
  text-align: center;

  color: #ffffff;
}

/* Step 4 */

.step-4 .title {
  position: absolute;
  width: 430px;
  height: 32px;
  left: 505px;
  top: 274px;

  font-family: Inter;
  font-style: normal;
  font-weight: bold;
  font-size: 32px;
  line-height: 100%;
  /* identical to box height, or 32px */

  display: flex;
  align-items: center;
  text-align: center;

  color: #0d1826;
}

.step-4 .tagline {
  position: absolute;
  width: 711px;
  height: 26px;
  left: 364px;
  top: 330px;

  font-family: Inter;
  font-style: normal;
  font-weight: normal;
  font-size: 18px;
  line-height: 145%;
  /* or 26px */

  text-align: center;

  color: #384b65;
}

.step-4 .storage-label {
  position: absolute;
  height: 22px;
  left: 588px;
  right: 662px;
  top: 408px;

  font-family: Inter;
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 134.09%;
  /* identical to box height, or 21px */

  display: flex;
  align-items: center;

  color: #384b65;
}

.step-4 .storage {
  position: absolute;
  left: 588px;
  right: 627px;
  top: 438px;
  bottom: 410px;

  background: #ffffff;
  border: 1px solid #dee4ed;
  box-sizing: border-box;
  border-radius: 6px;

  width: 225px;
  height: 52px;

  padding-left: 20px;

  font-family: Inter;
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 134.09%;
}

.step-4 .unit {
  position: absolute;
  left: 829px;
  right: 590px;
  top: 453px;
  bottom: 425px;

  font-family: Inter;
  font-style: normal;
  font-weight: 500;
  font-size: 16px;
  line-height: 134.09%;
  /* identical to box height, or 21px */

  display: flex;
  align-items: center;

  color: #384b65;

  z-index: 1000;
}

.step-4 .continue {
  position: absolute;
  left: 618px;
  right: 617px;
  top: 526px;
  bottom: 326px;

  /* acnt-1 */

  background: #2683ff;
  border-radius: 6px;

  width: 205px;
  height: 48px;

  font-family: Inter;
  font-style: normal;
  font-weight: bold;
  font-size: 16px;
  line-height: 135%;
  /* identical to box height, or 22px */

  align-items: center;
  text-align: center;

  color: #ffffff;
}

/* Step 5 */

.step-5 .title {
  position: absolute;
  width: 338px;
  height: 32px;
  left: 551px;
  top: 274px;

  font-family: Inter;
  font-style: normal;
  font-weight: bold;
  font-size: 32px;
  line-height: 100%;
  /* identical to box height, or 32px */

  display: flex;
  align-items: center;
  text-align: center;

  color: #0d1826;
}

.step-5 .tagline {
  position: absolute;
  width: 665px;
  height: 52px;
  left: 387px;
  top: 330px;

  font-family: Inter;
  font-style: normal;
  font-weight: normal;
  font-size: 18px;
  line-height: 145%;
  /* or 26px */

  text-align: center;

  color: #384b65;
}

.step-5 .directory-label {
  position: absolute;
  height: 22px;
  left: 445px;
  right: 735px;
  top: 434px;

  font-family: Inter;
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 134.09%;
  /* identical to box height, or 21px */

  display: flex;
  align-items: center;

  color: #384b65;
}

.step-5 .directory {
  position: absolute;
  left: 445px;
  right: 445px;
  top: 464px;
  bottom: 384px;

  width: 550px;
  height: 52px;

  background: #ffffff;
  border: 1px solid #dee4ed;
  box-sizing: border-box;
  border-radius: 6px;

  font-family: Inter;
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 134.09%;

  padding: 20px;
}

.step-5 .continue {
  position: absolute;
  left: 618px;
  right: 617px;
  top: 552px;
  bottom: 300px;

  background: #2683ff;
  border-radius: 6px;

  font-family: Inter;
  font-style: normal;
  font-weight: bold;
  font-size: 16px;
  line-height: 135%;

  text-align: center;

  color: #ffffff;

  width: 205px;
  height: 48px;
}

/* Step 5 */

.step-6 .title {
  position: absolute;
  width: 632px;
  height: 32px;
  left: 403px;
  top: 274px;

  font-family: Inter;
  font-style: normal;
  font-weight: bold;
  font-size: 32px;
  line-height: 100%;
  /* identical to box height, or 32px */

  display: flex;
  align-items: center;
  text-align: center;

  color: #0d1826;
}

.step-6 .tagline {
  position: absolute;
  width: 795px;
  height: 52px;
  left: 322px;
  top: 330px;

  font-family: Inter;
  font-style: normal;
  font-weight: normal;
  font-size: 18px;
  line-height: 145%;
  /* or 26px */

  text-align: center;

  color: #384b65;
}

.step-6 .host-label {
  position: absolute;
  height: 22px;
  left: 445px;
  right: 735px;
  top: 434px;

  font-family: Inter;
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 134.09%;
  /* identical to box height, or 21px */

  display: flex;
  align-items: center;

  color: #384b65;
}

.step-6 .host {
  position: absolute;
  left: 445px;
  right: 445px;
  top: 464px;
  bottom: 384px;

  background: #ffffff;
  border: 1px solid #dee4ed;
  box-sizing: border-box;
  border-radius: 6px;

  width: 550px;
  height: 52px;

  padding-left: 20px;

  font-family: Inter;
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 134.09%;
}

.step-6 .continue {
  position: absolute;
  left: 618px;
  right: 617px;
  top: 552px;
  bottom: 300px;

  /* acnt-1 */

  background: #2683ff;
  border-radius: 6px;

  width: 205px;
  height: 48px;

  font-family: Inter;
  font-style: normal;
  font-weight: bold;
  font-size: 16px;
  line-height: 135%;
  /* identical to box height, or 22px */

  align-items: center;
  text-align: center;

  color: #ffffff;
}

/* Step 6 */

.identity-step-1 .title {
  position: absolute;
  width: 380px;
  height: 32px;
  left: 530px;
  top: 274px;

  font-family: Inter;
  font-style: normal;
  font-weight: bold;
  font-size: 32px;
  line-height: 100%;
  /* identical to box height, or 32px */

  display: flex;
  align-items: center;
  text-align: center;

  color: #0d1826;
}

.identity-step-1 .tagline {
  position: absolute;
  width: 795px;
  height: 78px;
  left: 322px;
  top: 330px;

  font-family: Inter;
  font-style: normal;
  font-weight: normal;
  font-size: 18px;
  line-height: 145%;
  /* or 26px */

  text-align: center;

  color: #384b65;
}

.identity-step-1 .identity-label {
  position: absolute;
  height: 22px;
  left: 446px;
  right: 734px;
  top: 460px;

  font-family: Inter;
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 134.09%;
  /* identical to box height, or 21px */

  display: flex;
  align-items: center;

  color: #384b65;
}

.identity-step-1 .identity {
  position: absolute;
  left: 445px;
  right: 445px;
  top: 490px;
  bottom: 358px;

  background: #ffffff;
  border: 1px solid #dee4ed;
  box-sizing: border-box;
  border-radius: 6px;

  width: 550px;
  height: 52px;

  font-family: Inter;
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 134.09%;

  padding-left: 25px;
}

.identity-step-1 .no-identity {
  position: absolute;
  left: 509px;
  right: 726px;
  top: 578px;
  bottom: 274px;

  border: 1px solid #2683ff;
  box-sizing: border-box;
  border-radius: 6px;

  width: 205px;
  height: 48px;

  font-family: Inter;
  font-style: normal;
  font-weight: 600;
  font-size: 16px;
  line-height: 135%;
  /* identical to box height, or 22px */

  text-align: center;

  color: #2683ff;
}

.identity-step-1 .finish {
  position: absolute;
  left: 726px;
  right: 509px;
  top: 578px;
  bottom: 274px;

  /* acnt-1 */

  background: #2683ff;
  border-radius: 6px;

  width: 205px;
  height: 48px;

  font-family: Inter;
  font-style: normal;
  font-weight: bold;
  font-size: 16px;
  line-height: 135%;

  text-align: center;
  color: #ffffff;
}

.identity-step-2 .title {
  position: absolute;
  width: 353px;
  height: 32px;
  left: 543px;
  top: 274px;

  font-family: Inter;
  font-style: normal;
  font-weight: bold;
  font-size: 32px;
  line-height: 100%;
  /* identical to box height, or 32px */

  display: flex;
  align-items: center;
  text-align: center;

  color: #0d1826;
}

.identity-step-2 .tagline {
  position: absolute;
  width: 795px;
  height: 78px;
  left: 322px;
  top: 330px;

  font-family: Inter;
  font-style: normal;
  font-weight: normal;
  font-size: 18px;
  line-height: 145%;
  /* or 26px */

  text-align: center;

  color: #384b65;
}

.identity-step-2 .generate {
  position: absolute;
  left: 618px;
  right: 617px;
  top: 444px;
  bottom: 408px;

  /* acnt-1 */

  background: #2683ff;
  border-radius: 6px;

  width: 205px;
  height: 48px;

  font-family: Inter;
  font-style: normal;
  font-weight: bold;
  font-size: 16px;
  line-height: 135%;
  /* identical to box height, or 22px */

  text-align: center;
  color: #ffffff;
}

.identity-step-2 .warning {
  position: absolute;
  left: 33.96%;
  right: 31.18%;
  top: 61.33%;
  bottom: 34.11%;

  font-family: Inter;
  font-style: normal;
  font-weight: 500;
  font-size: 14px;
  line-height: 145%;
  /* or 20px */

  display: flex;
  align-items: center;

  color: #657181;
}

.identity-step-2 .warning-icon {
  position: absolute;
  width: 24px;
  height: 24px;
  left: 445px;
  top: 561px;

  border-radius: 12px;

  background: #2683ff;
}

.identity-step-2 .warning-icon img {
  position: absolute;
  left: 10.29px;
  top: 6.86px;

  height: 10.97px;

  z-index: 1000;
}

.identity-step-3 .title {
  position: absolute;
  width: 428px;
  height: 32px;
  left: 505px;
  top: 274px;

  font-family: Inter;
  font-style: normal;
  font-weight: bold;
  font-size: 32px;
  line-height: 100%;
  /* identical to box height, or 32px */

  display: flex;
  align-items: center;
  text-align: center;

  color: #0d1826;
}

.identity-step-3 .tagline {
  position: absolute;
  width: 811px;
  height: 52px;
  left: 314px;
  top: 330px;

  font-family: Inter;
  font-style: normal;
  font-weight: normal;
  font-size: 18px;
  line-height: 145%;
  /* or 26px */

  text-align: center;

  color: #384b65;
}

.identity-step-3 .logs {
  position: absolute;
  width: 500px;
  height: 174px;
  left: 470px;
  top: 418px;

  background: #fcfcfd;
  border: 1px solid #dee4ed;
  box-sizing: border-box;
  border-radius: 8px;

  font-family: Inter;
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 135%;

  color: #0d1826;
}

.identity-step-3 .finish {
  position: absolute;
  left: 617px;
  right: 618px;
  top: 644px;
  bottom: 208px;

  /* acnt-1 */

  background: #2683ff;
  border-radius: 6px;

  width: 205px;
  height: 48px;

  font-family: Inter;
  font-style: normal;
  font-weight: bold;
  font-size: 16px;
  line-height: 135%;

  text-align: center;

  color: #ffffff;
}

/* Step 8 */

.step-8 .profile {
  position: absolute;
  width: 176px;
  height: 176px;
  left: 631px;
  top: 255px;

  background: #dee4ed;
}

.step-8 .title {
  position: absolute;
  width: 264px;
  height: 32px;
  left: 588px;
  top: 479px;

  font-family: Inter;
  font-style: normal;
  font-weight: bold;
  font-size: 32px;
  line-height: 100%;
  /* identical to box height, or 32px */

  display: flex;
  align-items: center;
  text-align: center;

  color: #0d1826;
}

.step-8 .tagline {
  position: absolute;
  width: 665px;
  height: 26px;
  left: 387px;
  top: 535px;

  font-family: Inter;
  font-style: normal;
  font-weight: normal;
  font-size: 18px;
  line-height: 145%;
  /* or 26px */

  text-align: center;

  color: #384b65;
}

.step-8 .finish {
  position: absolute;
  left: 617px;
  right: 618px;
  top: 597px;
  bottom: 255px;

  /* acnt-1 */

  background: #2683ff;
  border-radius: 6px;

  font-family: Inter;
  font-style: normal;
  font-weight: bold;
  font-size: 16px;
  line-height: 135%;

  text-align: center;

  color: #ffffff;

  width: 205px;
  height: 48px;
}

.progress .bar-1 {
  position: absolute;
  width: 186px;
  height: 2px;
  left: 195px;
  top: 803px;

  background: #dee4ed;
}

.progress .bar-2 {
  position: absolute;
  width: 186px;
  height: 2px;
  left: 411px;
  top: 803px;

  background: #dee4ed;
}

.progress .bar-3 {
  position: absolute;
  width: 186px;
  height: 2px;
  left: 627px;
  top: 803px;

  background: #dee4ed;
}

.progress .bar-4 {
  position: absolute;
  width: 186px;
  height: 2px;
  left: 843px;
  top: 803px;

  background: #dee4ed;
}

.progress .bar-5 {
  position: absolute;
  width: 186px;
  height: 2px;
  left: 1059px;
  top: 803px;

  background: #dee4ed;
}

.progress .bar-active {
  background: #2683ff !important;
}

.progress .point-1 {
  position: absolute;
  width: 32px;
  height: 32px;
  left: 164px;
  top: 787px;
  background: #dee4ed;

  border-radius: 16px;
}

.progress .label-1 {
  position: absolute;
  width: 8px;
  height: 21px;
  left: 176px;
  top: 793px;

  font-family: Inter;
  font-style: normal;
  font-weight: 600;
  font-size: 16px;
  line-height: 134.09%;
  /* or 21px */

  display: flex;
  align-items: center;
  text-align: center;

  color: #ffffff;
}

.progress .point-2 {
  position: absolute;
  width: 32px;
  height: 32px;
  left: 380px;
  top: 787px;

  background: #dee4ed;

  border-radius: 16px;
}

.progress .label-2 {
  position: absolute;
  width: 10px;
  height: 21px;
  left: 391px;
  top: 793px;

  font-family: Inter;
  font-style: normal;
  font-weight: 600;
  font-size: 16px;
  line-height: 134.09%;
  /* or 21px */

  display: flex;
  align-items: center;
  text-align: center;

  color: #929daa;
}

.progress .point-3 {
  position: absolute;
  width: 32px;
  height: 32px;
  left: 596px;
  top: 787px;

  background: #dee4ed;

  border-radius: 16px;
}

.progress .label-3 {
  position: absolute;
  width: 11px;
  height: 21px;
  left: 606px;
  top: 793px;

  font-family: Inter;
  font-style: normal;
  font-weight: 600;
  font-size: 16px;
  line-height: 134.09%;
  /* or 21px */

  display: flex;
  align-items: center;
  text-align: center;

  color: #929daa;
}

.progress .point-4 {
  position: absolute;
  width: 32px;
  height: 32px;
  left: 812px;
  top: 787px;

  background: #dee4ed;

  border-radius: 16px;
}

.progress .label-4 {
  position: absolute;
  width: 11px;
  height: 21px;
  left: 822px;
  top: 793px;

  font-family: Inter;
  font-style: normal;
  font-weight: 600;
  font-size: 16px;
  line-height: 134.09%;
  /* or 21px */

  display: flex;
  align-items: center;
  text-align: center;

  color: #929daa;
}

.progress .point-5 {
  position: absolute;
  width: 32px;
  height: 32px;
  left: 1028px;
  top: 787px;

  background: #dee4ed;

  border-radius: 16px;
}

.progress .label-5 {
  position: absolute;
  width: 11px;
  height: 21px;
  left: 1038px;
  top: 793px;

  font-family: Inter;
  font-style: normal;
  font-weight: 600;
  font-size: 16px;
  line-height: 134.09%;
  /* or 21px */

  display: flex;
  align-items: center;
  text-align: center;

  color: #929daa;
}

.progress .point-6 {
  position: absolute;
  width: 32px;
  height: 32px;
  left: 1244px;
  top: 787px;

  background: #dee4ed;

  border-radius: 16px;
}

.progress .label-6 {
  position: absolute;
  width: 11px;
  height: 21px;
  left: 1254px;
  top: 793px;

  font-family: Inter;
  font-style: normal;
  font-weight: 600;
  font-size: 16px;
  line-height: 134.09%;
  /* or 21px */

  display: flex;
  align-items: center;
  text-align: center;

  color: #929daa;
}

.progress .point-active {
  background: #2683ff;
}

.progress .label-active {
  color: #ffffff;
}

.progress .text-active {
  color: #384b65 !important;
}

.progress .text-1 {
  position: absolute;
  width: 109px;
  height: 21px;
  left: 126px;
  top: 831px;

  font-family: Inter;
  font-style: normal;
  font-weight: 500;
  font-size: 16px;
  line-height: 134.09%;
  /* or 21px */

  display: flex;
  align-items: center;
  text-align: center;

  color: rgba(56, 75, 101, 0.4);
}

.progress .text-2 {
  position: absolute;
  width: 152px;
  height: 21px;
  left: 319px;
  top: 831px;

  font-family: Inter;
  font-style: normal;
  font-weight: 500;
  font-size: 16px;
  line-height: 134.09%;
  /* or 21px */

  display: flex;
  align-items: center;
  text-align: center;

  color: rgba(56, 75, 101, 0.4);
}

.progress .text-3 {
  position: absolute;
  width: 141px;
  height: 21px;
  left: 542px;
  top: 831px;

  font-family: Inter;
  font-style: normal;
  font-weight: 500;
  font-size: 16px;
  line-height: 134.09%;
  /* or 21px */

  display: flex;
  align-items: center;
  text-align: center;

  color: rgba(56, 75, 101, 0.4);
}

.progress .text-4 {
  position: absolute;
  width: 136px;
  height: 21px;
  left: 759px;
  top: 831px;

  font-family: Inter;
  font-style: normal;
  font-weight: 500;
  font-size: 16px;
  line-height: 134.09%;
  /* or 21px */

  display: flex;
  align-items: center;
  text-align: center;

  color: rgba(56, 75, 101, 0.4);
}

.progress .text-5 {
  position: absolute;
  width: 123px;
  height: 21px;
  left: 982px;
  top: 831px;

  font-family: Inter;
  font-style: normal;
  font-weight: 500;
  font-size: 16px;
  line-height: 134.09%;
  /* or 21px */

  display: flex;
  align-items: center;
  text-align: center;

  color: rgba(56, 75, 101, 0.4);
}

.progress .text-6 {
  position: absolute;
  width: 59px;
  height: 21px;
  left: 1230px;
  top: 831px;

  font-family: Inter;
  font-style: normal;
  font-weight: 500;
  font-size: 16px;
  line-height: 134.09%;
  /* or 21px */

  display: flex;
  align-items: center;
  text-align: center;

  color: rgba(56, 75, 101, 0.4);
}
</style>
