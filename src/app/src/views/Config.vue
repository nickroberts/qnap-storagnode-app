<template>
  <v-container fluid>
    <div class="config">
      <ConfigCard />
      <ConfigCard />
    </div>
  </v-container>
</template>

<script>
import ConfigCard from "@/components/ConfigCard.vue";

export default {
  name: "Config",
  components: {
    ConfigCard,
  },
};
</script>

<style lang="scss" scoped>
.config {
  > * {
    margin-top: 2rem;
    &:first-of-type {
      margin-top: 0;
    }
  }
}
</style>
