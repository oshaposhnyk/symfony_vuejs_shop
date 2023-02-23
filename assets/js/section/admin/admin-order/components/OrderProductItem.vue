<template>
  <tr>
    <td>{{ rowNumber }}</td>
    <td>{{ productTitle }}</td>
    <td>{{ categoryTitle }}</td>
    <td>{{ orderProduct.quantity }}</td>
    <td>{{ orderProduct.pricePerOne }}</td>
    <td class="d-flex justify-space-between">
      <v-btn color="info" small @click="viewDetails">{{ viewDetailsBtnText }}</v-btn>
      <v-btn color="error" small @click="removeItem">{{ removeItemBtnText }}</v-btn>
    </td>
  </tr>
</template>

<script>
  import {mapState, mapActions} from "vuex";

  export default {
    name: "OrderProductItem",
    props: {
      orderProduct: {
        type: Object,
        default: () => {}
      },
      index: {
        type: Number,
        default: 0
      }
    },
    data() {
      return {
        localOrderProduct: this.orderProduct
      }
    },
    computed: {
      ...mapState("products", ["staticStore"]),
      rowNumber() {
        return this.index + 1;
      },
      productTitle() {
        return this.localOrderProduct.product.title
      },
      categoryTitle() {
        return this.localOrderProduct.product.category.title;
      },
      viewDetailsBtnText() {
        return "Details";
      },
      removeItemBtnText() {
        return "Remove";
      }
    },
    methods: {
      ...mapActions("products", ["removeOrderProduct"]),
      viewDetails(event) {
        event.preventDefault();
        this.$store.dispatch("openProductDetailsWindow", this.localOrderProduct.product.id);
      },
      removeItem(event) {
        event.preventDefault();
        this.removeOrderProduct(this.localOrderProduct.id);
            // .then(() => {
            //   this.localOrderProduct = {};
            // });

      }
    }
  }
</script>