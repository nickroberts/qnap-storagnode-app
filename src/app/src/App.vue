<template>
  <v-app class="app">
    <v-navigation-drawer app color="secondary" dark v-model="drawer">
      <div class="logo px-4 py-2 d-flex align-center justify-start">
        <v-img
          alt="Storj Logo"
          class="shrink"
          contain
          src="./assets/logo.svg"
          transition="scale-transition"
          height="50"
          width="100"
        />
        <div class="ml-4">|</div>
        <div class="ml-4">QNAP</div>
      </div>

      <v-list dense>
        <v-list-item link router-link to="/">
          <v-list-item-action>
            <v-icon>mdi-view-dashboard</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Dashboard</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link router-link to="/config">
          <v-list-item-action>
            <v-icon>mdi-cog</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>
              Config
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link router-link to="/wizard">
          <v-list-item-action>
            <v-icon>mdi-restart</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Wizard</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>

      <v-spacer></v-spacer>

      <v-list dense>
        <v-list-item link href="#">
          <v-list-item-action>
            <v-icon>mdi-file-document</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Documentation</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      app
      dark
      class="storj-gradient"
      v-if="$vuetify.breakpoint.mdAndDown"
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
      <v-spacer></v-spacer>
      <div class="logo px-4 py-2 d-flex align-center justify-end">
        <v-img
          alt="Storj Logo"
          class="shrink"
          contain
          src="./assets/logo.svg"
          transition="scale-transition"
          height="50"
          width="100"
        />
        <div class="ml-4">|</div>
        <div class="ml-4">QNAP</div>
      </div>
    </v-app-bar>

    <v-content>
      <v-container fluid class="fill-height align-start">
        <transition name="slide-fade" mode="out-in">
          <router-view></router-view>
        </transition>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
export default {
  name: "App",
  components: {},
  data: () => ({
    copyright: new Date().getFullYear(),
    drawer: null,
  }),
  props: {},
};
</script>

<style lang="scss">
.v-application {
  background: #f0f2f4 !important;
}

.logo {
  color: white;
}

.storj-gradient {
  background-image: linear-gradient(to right, #02040b, #0c2d75);
}

.slide-fade-enter-active {
  transition: all 0.125s ease-in-out;
}

.slide-fade-leave-active {
  transition: all 0.125s ease-in-out;
}

.slide-fade-enter,
.slide-fade-leave-to {
  transform: translateY(20px);
  opacity: 0;
}

// Vuetify overrides
.v-btn {
  font-weight: 700 !important;
  text-transform: none !important;
}

.v-input__slot {
  background: white !important;
}

.v-stepper__step__step {
  width: 32px !important;
  height: 32px !important;
  min-width: 32px !important;
}

.v-stepper__step__step {
  font-size: 18px !important;
}

.v-navigation-drawer__content {
  display: flex !important;
  flex-flow: column nowrap !important;
}
</style>
