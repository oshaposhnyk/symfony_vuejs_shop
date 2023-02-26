<template>
  <div class="actions">
    <v-btn
        class="add"
        @click.prevent="viewCart"
        color="success"
        small
    >View cart</v-btn>
    <v-btn
        class="details mt-2"
        @click.prevent="clearCart"
        color="info"
        small
    >Checkout</v-btn>
  </div>
</template>

<script>
import {mapState, mapActions} from "vuex";

export default {
  name: "CartActions",
  computed: {
    ...mapState("cart", ["staticStore"])
  },
  methods: {
    ...mapActions("cart", ["clearCart"]),
    viewCart() {
      const url = this.staticStore.url.urlCart;
      window.open(url, '_blank');
    }
  }
}
</script>

<style scoped>

</style>