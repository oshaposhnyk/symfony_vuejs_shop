<template>
  <div class="dropdown cart-dropdown">
    <a href="" class="cart-dropdown-btn-toggle">
      <i class="fas fa-shopping-cart"></i>
      <span class="count">{{countCartProducts}}</span>
    </a>

    <div class="dropdown-menu cart-dropdown-window">
      <cart-product-list />
      <cart-total-price />

      <cart-actions />
    </div>

  </div>

</template>

<script>
import CartTotalPrice from "./components/CartTotalPrice.vue";
import CartActions from "./components/CartActions.vue";
import CartProductList from "./components/CartProductList.vue";
import {mapActions, mapState} from "vuex";

export default {
  name: "App",
  components: {CartProductList, CartActions, CartTotalPrice},
  created() {
    this.getCart();
  },
  computed: {
    ...mapState("cart", ["cart"]),
    countCartProducts() {
      return this.cart.cartProducts ? this.cart.cartProducts.length : 0;
    }
  },
  methods: {
    ...mapActions("cart", ["getCart", "createCart"])
  }
}
</script>

<style scoped>

</style>