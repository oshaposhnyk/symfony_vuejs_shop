<template>
  <div>
    vue app {{productsCount}}
  </div>
</template>

<script>
export default {
  name: "App",
  computed: {
    productsCount: () => {
      return 123;
    }
  }
}
</script>

<style scoped>

</style>