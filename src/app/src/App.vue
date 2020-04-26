<template>
  <v-app>
    <v-navigation-drawer app clipped color="secondary" dark v-model="drawer">
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
    </v-navigation-drawer>

    <v-app-bar app clipped-left dark class="storj-gradient">
      <v-toolbar-title>
        <router-link to="/">
          <v-img
            alt="Storj Logo"
            class="shrink mr-2"
            contain
            src="./assets/logo.svg"
            transition="scale-transition"
          />
        </router-link>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-app-bar-nav-icon
        class="hidden-lg-and-up"
        @click.stop="drawer = !drawer"
      />
    </v-app-bar>

    <v-content>
      <v-container fluid class="fill-height align-start">
        <transition name="slide-fade" mode="out-in">
          <router-view></router-view>
        </transition>
      </v-container>
    </v-content>

    <v-footer app dark class="storj-gradient">
      <span>Storj &copy; {{ copyright }}</span>
    </v-footer>
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
.v-btn {
  font-weight: 700 !important;
  text-transform: none !important;
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
</style>
