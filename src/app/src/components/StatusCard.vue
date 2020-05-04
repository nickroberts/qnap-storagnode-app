<template>
  <div>
    <div class="title">Node Status</div>

    <div v-if="status">
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
          <v-list-item-subtitle v-if="status.dockerInfo">{{ status.dockerInfo.status }}</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>

      <v-list-item class="px-0" v-if="status.dockerInfo">
        <v-list-item-content>
          <v-list-item-title>Container Image:</v-list-item-title>
          <v-list-item-subtitle>{{ status.dockerInfo.image }}</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>

      <v-list-item class="px-0" v-if="status.dockerInfo">
        <v-list-item-content>
          <v-list-item-title>Container Name:</v-list-item-title>
          <v-list-item-subtitle>{{ status.dockerInfo.names }}</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>
    </div>
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
