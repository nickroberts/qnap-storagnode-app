<template>
  <div>
    <div class="title">Storage Node Version</div>

    <v-list-item class="px-0" v-if="status">
      <v-list-item-content>
        <v-list-item-title>Version:</v-list-item-title>
        <v-list-item-subtitle v-if="status.versionInfo">{{ status.versionInfo.version }}</v-list-item-subtitle>
      </v-list-item-content>
    </v-list-item>
  </div>
</template>

<script>
export default {
  name: 'VersionCard',
  components: {},
  props: {
    status: Object
  }
};
</script>
