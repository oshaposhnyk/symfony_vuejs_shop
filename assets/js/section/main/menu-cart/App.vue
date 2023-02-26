<template>
  <div class="dropdown cart-dropdown">
    <a href="" class="cart-dropdown-btn-toggle">
      <i class="fas fa-shopping-cart"></i>
      <span class="count">15</span>
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

export default {
  name: "App",
  components: {CartProductList, CartActions, CartTotalPrice}
}
</script>

<style scoped>

</style>