<template>
  <div>
    <div class="title">Node Status</div>

    <v-list-item class="px-0">
      <v-list-item-content>
        <v-list-item-title>
          <span v-if="status.status !== 'online'" class="red--text font-weight-bold">
            Offline:
          </span>
          <span v-if="status.status === 'online'" class="green--text font-weight-bold">
            Online:
          </span>
        </v-list-item-title>
        <v-list-item-subtitle>{{ status.dockerInfo.status }}</v-list-item-subtitle>
      </v-list-item-content>
    </v-list-item>

    <slot />
  </div>
</template>

<script>
export default {
  name: 'StatusCard',
  components: {},
  props: {
    status: Object
  }
};
</script>
