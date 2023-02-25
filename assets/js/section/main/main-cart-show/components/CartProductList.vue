<template>
  <div>
    <v-table
        fixed-header
        height="500px"
    >
      <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
        <th>Actions</th>
      </tr>
      </thead>
      <tbody>
        <card-product-item
          v-for="cartProduct in cart.cartProducts"
          :key="cartProduct.id"
          :cart-product="cartProduct"
          />
      </tbody>
    </v-table>
  </div>
</template>

<script>
import {mapState} from "vuex";
import CardProductItem from "./CardProductItem.vue";
import CartTotalPrice from "./CartTotalPrice.vue";

export default {
  components: {CartTotalPrice, CardProductItem},
  data() {
    return {
      cartProducts: [],
    };
  },
  computed: {
    ...mapState("cart", ["cart"]),
  },
  methods: {
    removeProduct(index) {
      this.cartProducts.splice(index, 1);
    },
  },
};
</script>
