<template>
  <v-container>
    <flash-message ref="FlashMessage" />
    <v-row align="center" class="mb-2">
      <v-col cols="12" md="2">
        <v-select
            v-model="form.categoryId"
            name="add_product_category_select"
            label="Category"
            :items="categories"
            item-text="title"
            item-value="id"
            clearable
        />
      </v-col>
      <v-col cols="12" md="3">
        <v-select
            v-model="form.productId"
            v-if="form.categoryId"
            name="add_product_product_select"
            label="Product"
            :items="freeCategoryProducts"
            item-text="title"
            item-value="uuid"
            clearable
        />
      </v-col>
      <v-col cols="12" md="2">
        <v-text-field
            v-model="form.quantity"
            type="number"
            label="Quantity"
            placeholder="quantity"
            min="1"
            v-if="form.productId"
            :max="productQuantityMax"
        />
      </v-col>
      <v-col cols="12" md="2">
        <v-text-field
            v-model="form.pricePerOne"
            type="number"
            label="Price per one"
            placeholder="price per one"
            min="1"
            step="0.01"
            v-if="form.productId"
            :max="productPriceMax"
        />
      </v-col>
      <v-col cols="12" md="3" class="d-flex justify-space-between">
        <v-btn
            class="details"
            v-if="form.productId"
            @click.prevent="viewDetails"
            color="info"
            small
        >Details</v-btn>
        <v-btn
            class="add"
            v-if="form.productId && form.categoryId && form.pricePerOne && form.quantity"
            @click.prevent="submit"
            color="success"
            small
        >Add</v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import {mapActions, mapMutations, mapState, mapGetters} from "vuex";
import {getProductInformativeTitle} from "../utils/title-formatter";
import FlashMessage from "./FlashMessage.vue";

export default {
  name: "OrderProductAdd",
  components: {
    FlashMessage
  },
  data() {
    return {
      form: {
        categoryId: "",
        productId: "",
        quantity: "",
        pricePerOne: ""
      },
    };
  },
  watch: {
    'form.categoryId': function() {
      this.getProducts();
    }
  },
  computed: {
    ...mapGetters("products", ["freeCategoryProducts"]),
    ...mapState("products", ["categories", "categoryProducts"]),
    productQuantityMax() {
      const productData = this.freeCategoryProducts.find(
          product => product.uuid === this.form.productId
      );
      return parseInt(productData.quantity)
    },
    productPriceMax() {
      const productData = this.freeCategoryProducts.find(
          product => product.uuid === this.form.productId
      );
      return parseFloat(productData.price)
    }
  },
  methods: {
    ...mapMutations("products", ["setNewProductInfo"]),
    ...mapActions("products", ["getProductsByCategory", "addNewOrderProduct"]),
    productTitle(product) {
      return getProductInformativeTitle(product);
    },
    getProducts() {
      this.setNewProductInfo(this.form);
      this.getProductsByCategory();
    },
    viewDetails() {
      this.$store.dispatch("openProductDetailsWindow", this.form.productId);
    },
    submit() {
      this.setNewProductInfo(this.form);
      this.addNewOrderProduct();
      this.resetFormData();
      this.openSnackbar();
    },
    resetFormData() {
      Object.assign(this.$data, this.$options.data.apply(this));
    },
    openSnackbar() {
      this.$refs.FlashMessage.showSnackbar({
        text: "Success",
        color: "success",
        timeout: 5000,
        actionText: "Close",
        action: () => {
          this.$refs.FlashMessage.snackbar.show = false;
        },
      });
    }
  }
}
</script>

<style scoped>

</style>