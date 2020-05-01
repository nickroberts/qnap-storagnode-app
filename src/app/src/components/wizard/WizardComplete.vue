<template>
  <div>
    <h1>Congratulations!</h1>
    <p>You finished the quest and ready to go</p>
    <v-btn color="primary" x-large @click="$emit('step-complete')">Finish</v-btn>
  </div>
</template>

<script>
export default {
  name: 'WizardComplete',
  components: {}
};
</script>

<style lang="scss" scoped></style>
