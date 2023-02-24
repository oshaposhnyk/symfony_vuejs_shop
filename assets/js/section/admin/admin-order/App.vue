<template>
  <OrderProductAdd />
  <v-table fixed-header height="300px">
    <thead>
    <tr>
      <th class="text-left"> № </th>
      <th class="text-left"> Title </th>
      <th class="text-left"> Category </th>
      <th class="text-left"> Quantity </th>
      <th class="text-left"> Price </th>
      <th class="text-center"> Actions </th>
    </tr>
    </thead>
    <tbody>
      <OrderProductItem v-for="(orderProduct, index) in orderProducts" :key="orderProduct.id" :order-product="orderProduct" :index="index" />
    </tbody>
  </v-table>
  <hr>
  <total-price-block />
</template>

<script>
import {mapActions, mapState} from "vuex";
import OrderProductItem from "./components/OrderProductItem.vue";
import OrderProductAdd from "./components/OrderProductAdd.vue";
import TotalPriceBlock from "./components/TotalPriceBlock.vue";

export default {
  components: {TotalPriceBlock, OrderProductAdd, OrderProductItem},
  created() {
    this.getCategories();
    this.getOrderProducts();
  },
  computed: {
    ...mapState({
        orderProducts: state => state.products.orderProducts
    }),
    productsCount: () => 123
  },
  methods: {
    ...mapActions("products", ["getCategories", "getOrderProducts"]),
  }
}
</script>

<style scoped>
  thead {
    background-color: #eee; /* або будь-який інший сірий колір */
  }
</style>