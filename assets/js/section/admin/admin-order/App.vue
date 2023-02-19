<template>
  <div class="table-additional-selection">
    <OrderProductAdd/>
    <hr>
      <OrderProductItem
        v-for="(orderProduct, index) in orderProducts"
        key="orderProduct.id"
        :order-product="orderProduct"
        :index="index"
        />
    <hr>
  </div>
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