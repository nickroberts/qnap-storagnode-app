<template>
  <div>
    <div class="title">Storage Node Version</div>

    <v-list-item class="px-0">
      <v-list-item-content>
        <v-list-item-title>Version:</v-list-item-title>
        <v-list-item-subtitle>{{ status.versionInfo.version }}</v-list-item-subtitle>
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
