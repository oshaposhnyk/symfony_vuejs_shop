<template>
  <div class="actions">
    <v-btn
        class="add"
        @click.prevent=""
        color="success"
        small
    >View cart</v-btn>
    <v-btn
        class="details mt-2"
        @click.prevent=""
        color="info"
        small
    >Checkout</v-btn>
  </div>
</template>

<script>
export default {
  name: "CartActions"
}
</script>

<style scoped>

</style>