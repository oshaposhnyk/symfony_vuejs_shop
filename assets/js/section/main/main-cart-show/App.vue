<template>
  <div class="row">
    <div class="col-lg-12 order-block">
      <div class="order-conten">
        <div v-if="showCartContent">
          <cart-product-list />
          <cart-total-price />
          <v-btn color="success" small @click="makeOrder"> Make order</v-btn>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CartProductList from "./components/CartProductList.vue";
import CartTotalPrice from "./components/CartTotalPrice.vue";

export default {
  name: "App",
  components: {CartTotalPrice, CartProductList},
  computed: {
    showCartContent() {
      return true;
    }
  },
  methods: {
    makeOrder() {
      console.log('Make order');
    }
  }
}
</script>

<style scoped>

</style>