<template>
  <v-card class="config-card mx-auto" width="100%" outlined>
    <div class="d-flex align-center">
      <v-avatar class="ma-3" size="125" tile>
        <v-icon color="primary" size="96">mdi-fingerprint</v-icon>
      </v-avatar>

      <div>
        <v-card-title>
          <span class="title font-weight-light">Identity</span>
        </v-card-title>

        <v-card-text>
          Every node is required to have a unique identifier on the network. If
          you haven't already, get an authorization token. Please get the
          authorization token and create identity on host machine other than
          NAS.
        </v-card-text>

        <v-card-actions class="actions">
          <v-btn color="primary" outlined>Edit Authorization Token </v-btn>
        </v-card-actions>

        <v-card-text>
          <div
            style="font-weight: bold; border: 1px solid gray; padding: 16px;"
          >
            Identity generation STATUS(2020-04-26 07:34:41):<br />
            Process ID: , Started at: 2020-04-26 07:20:41<br />
            /root/.local/share/storj/identity/storagenode/ca.key
          </div>
        </v-card-text>
      </div>
    </div>
  </v-card>
</template>

<script>
export default {
  name: "ConfigCard",
  components: {},
};
</script>

<style lang="scss" scoped>
.actions {
  padding-left: 16px;
  padding-right: 16px;
}
</style>
