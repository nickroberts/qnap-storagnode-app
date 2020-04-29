<template>
  <v-card class="fill-height pa-4" outlined>
    <v-container class="fill-height">
      <div class="d-flex fill-height align-center">
        <v-icon class="mr-4" color="primary" size="64">{{
          $props.icon
        }}</v-icon>

        <div class="d-flex flex-column fill-height">
          <v-card-title class="mt-0 pt-0">
            <div class="d-flex">
              <div>
                {{ $props.title }}
              </div>
              <v-tooltip
                class="info-icon"
                v-if="$props.info"
                bottom
                max-width="500"
              >
                <template v-slot:activator="{ on }">
                  <v-btn icon v-on="on">
                    <v-icon>info</v-icon>
                  </v-btn>
                </template>
                <span>{{ $props.info }}</span>
              </v-tooltip>
            </div>
          </v-card-title>

          <v-card-text>{{ $props.value }}</v-card-text>

          <v-spacer></v-spacer>

          <slot />
        </div>
      </div>
    </v-container>
  </v-card>
</template>

<script>
export default {
  name: "ConfigCard",
  components: {},
  props: {
    icon: String,
    title: String,
    value: String,
    info: String,
  },
};
</script>

<style lang="scss" scoped>
.info-icon {
  margin-left: 10px;
  margin-top: -2px;
}
</style>
