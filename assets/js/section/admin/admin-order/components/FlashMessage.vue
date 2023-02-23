<template>
  <div v-if="message" :class="'alert alert-' + message.type + ' alert-dismissible fade show text-center'" role="alert">
    {{ message.text }}
  </div>
</template>

<script>
export default {
  props: {
    message: {
      type: Object,
      default: null
    }
  }
}
</script>

<style scoped>

</style>
