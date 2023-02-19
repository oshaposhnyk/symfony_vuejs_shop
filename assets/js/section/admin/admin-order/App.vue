<template>
  <div class="table-additional-selection">
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
import {mapState} from "vuex";
import OrderProductItem from "./components/OrderProductItem.vue";

export default {
  components: {OrderProductItem},
  created() {
  },
  computed: {
    ...mapState({
        orderProducts: state => state.products.staticStore.orderProducts,
    }),
    productsCount: () => 123
  }
}
</script>

<style scoped>

</style>