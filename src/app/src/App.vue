<template>
  <v-app class="app">
    <v-navigation-drawer app color="primary" dark temporary v-model="drawer">
      <v-list dense>
        <v-list-item
          v-for="item in items"
          :key="item.title"
          link
          router-link
          :to="item.to"
          :href="item.href"
          :target="item.target"
        >
          <v-list-item-action>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar app elevation="0" color="white" height="120">
      <v-app-bar-nav-icon v-if="$vuetify.breakpoint.smAndDown" light @click.stop="drawer = !drawer" />

      <div class="logo px-4 py-2 d-flex align-center justify-end headline">
        <router-link to="/">
          <v-img
            alt="Storj Logo"
            class="shrink"
            contain
            src="./assets/logo.svg"
            transition="scale-transition"
            height="50"
            width="125"
          />
        </router-link>
        <div class="ml-4">|</div>
        <div class="ml-4">Storage Node</div>
      </div>

      <v-spacer></v-spacer>

      <nav v-if="!$vuetify.breakpoint.smAndDown">
        <v-btn
          class="ml-2"
          color="transparent"
          v-for="item in items"
          :key="item.title"
          link
          elevation="0"
          router-link
          :to="item.to"
          :href="item.href"
          :target="item.target"
        >
          <v-icon left>{{ item.icon }}</v-icon>
          {{ item.title }}
        </v-btn>
      </nav>
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
import { mapActions, mapState } from 'vuex';

export default {
  name: 'App',
  components: {},
  data: () => ({
    copyright: new Date().getFullYear(),
    drawer: false,
    items: [
      {
        icon: 'dashboard',
        title: 'Dashboard',
        to: '/'
      },
      {
        icon: 'settings',
        title: 'Setup',
        to: '/setup'
      },
      {
        icon: 'build',
        title: 'Wizard',
        to: '/wizard'
      },
      {
        icon: 'description',
        title: 'Documentation',
        href: '#',
        target: '_blank'
      }
    ]
  }),
  created: function() {
    this['config/loadConfig']();
    this['status/loadStatus']();
  },
  props: {},
  computed: {
    ...mapState({
      configData: state => state.config.data
    })
  },
  methods: {
    ...mapActions(['config/loadConfig', 'config/saveConfig', 'status/loadStatus'])
  }
};
</script>

<style lang="scss">
.v-application {
  background: #f0f2f4 !important;

  a {
    text-decoration: none;
  }
}

.v-app-bar {
  nav {
    .v-btn,
    .v-btn:before,
    .v-btn.v-btn--active {
      background-color: transparent;
    }
    .v-btn {
      color: var(--v-primary-base);
    }
  }
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
  letter-spacing: 0 !important;
  text-indent: 0 !important;
  text-decoration-color: none !important;
  text-decoration-style: none !important;
  text-decoration-line: none !important;
  -webkit-font-smoothing: none !important;
  &.v-size--large {
    font-size: 1rem !important;
    height: 48px !important;
  }
  /* &:not(.v-btn--icon):not(.v-btn--block):not(.no-min-width) {
    min-width: 200px !important;
  } */
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

.v-dialog {
  .v-card__actions {
    padding: 12px 24px;
    /* .v-btn {
      &:not(.v-btn--icon):not(.v-btn--block) {
        min-width: 100px !important;
      }
    } */
  }
}

.form-control {
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

.config-modal {
  /* &:not(.v-btn--icon):not(.v-btn--block) {
    min-width: 100px !important;
  } */
  .config-modal-activator {
    padding: 0 16px;
  }
}
</style>
