<template>
  <div class="row">
    <div class="col-lg-12 order-block">
      <div class="order-content">
        <Alert />
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
import Alert from "./components/Alert.vue";
import {mapActions, mapState} from "vuex";

export default {
  name: "App",
  created() {
    this.getCart();
  },
  components: {CartTotalPrice, CartProductList, Alert},
  computed: {
    showCartContent() {
      return true;
    },
  },
  methods: {
    ...mapActions("cart", ["getCart"]),
    makeOrder() {
      console.log('Make order');
    }
  }
}
</script>

<style scoped>

</style>