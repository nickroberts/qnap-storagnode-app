<template>
  <v-card outlined>
    <v-container>
      <v-row class="fill-height">
        <v-col cols="auto">
          <v-icon color="primary" size="96">{{ $props.icon }}</v-icon>
        </v-col>

        <v-col>
          <v-card-title>
            <div class="d-flex">
              <div>
                {{ $props.title }}
              </div>
              <v-tooltip class="info-icon" v-if="$props.info" bottom>
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

          <slot />
        </v-col>
      </v-row>
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
