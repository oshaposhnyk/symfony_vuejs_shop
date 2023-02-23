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
</template>

<script>
import {mapActions, mapState} from "vuex";
import OrderProductItem from "./components/OrderProductItem.vue";
import OrderProductAdd from "./components/OrderProductAdd.vue";

export default {
  components: {OrderProductAdd, OrderProductItem},
  created() {
    this.getCategories();
  },
  computed: {
    ...mapState({
        orderProducts: state => state.products.staticStore.orderProducts,
    }),
    productsCount: () => 123
  },
  methods: {
    ...mapActions("products", ["getCategories"]),
  }
}
</script>

<style scoped>

</style>