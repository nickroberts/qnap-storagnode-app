<template>
  <div class="text-center">
    <v-img alt="Storj Logo" class="shrink logo" contain src="../../assets/emblem.svg" transition="scale-transition" />
    <h1 class="mt-0 mb-2">Welcome to Storj!</h1>
    <p class="mb-6">
      Monetize your excess capacity on the Storj Network
    </p>
    <v-btn color="primary" x-large @click="$emit('step-complete')">Start</v-btn>
  </div>
</template>

<script>
export default {
  name: 'WizardWelcome',
  components: {}
};
</script>

<style lang="scss" scoped>
.logo {
  height: 75px;
  margin-bottom: 80px;
}
</style>
