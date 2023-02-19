<template>
  <div class="row mb-1">
    <div class="col-mb-1 text-center">
      {{ rowNumber }}
    </div>
    <div class="col-md-3">
      {{ productTitle }}
    </div>
    <div class="col-md-2">
      {{ categoryTitle }}
    </div>
    <div class="col-md-2">
      {{ orderProduct.quantity }}
    </div>
    <div class="col-md-2">
      {{ orderProduct.pricePerOne }}
    </div>
    <div class="col-md-2">
      <button class="btn btn-info btn-sm" @click="viewDetails">{{ viewDetailsBtnText }}</button>
      <button class="btn btn-danger btn-sm" @click="removeItem">{{ removeItemBtnText }}</button>
    </div>
  </div>
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