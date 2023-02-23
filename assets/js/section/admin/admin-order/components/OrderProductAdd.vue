<template>
  <div>
    <div class="row mb-2">
      <div class="col-md-2">
        <select
          v-model="form.categoryId"
          name="add_product_category_select"
          class="form-control"
          @change="getProducts()"
        >
          <option value="" disabled>- choose options -</option>
          <option
            v-for="category in categories"
            :key="category.id"
            :value="category.id"
          >{{ category.title }}</option>
        </select>
      </div>
      <div class="col-md-3">
        <select
            v-model="form.productId"
            v-if="form.categoryId"
            name="add_product_product_select"
            class="form-control"
        >
          <option value="" disabled>- choose options -</option>
          <option
              v-for="product in categoryProducts"
              :key="product.id"
              :value="product.uuid"
          >{{ productTitle(product) }}</option>
        </select>
      </div>
      <div class="col-md-2">
        <input
            v-model="form.quantity"
            type="number"
            class="form-control"
            placeholder="quantity"
            min="1"
            v-if="form.productId"
        />
      </div>
      <div class="col-md-2">
        <input
            v-model="form.pricePerOne"
            type="number"
            class="form-control"
            step="0.01"
            placeholder="price per one"
            min="0"
            v-if="form.productId"
        />
      </div>
      <div class="col-md-2 d-flex justify-content-between">
        <button
            class="btn btn-info btn-sm details"
            v-if="form.productId"
            @click="viewDetails"
        >Details</button>
        <button
            class="btn btn-success btn-sm add"
            v-if="form.productId && form.categoryId && form.pricePerOne && form.quantity"
            @click="submit"
        >Add</button>
      </div>
    </div>
    <flash-message :message="flashMessage" />
  </div>
</template>

<script>
import {mapActions, mapMutations, mapState} from "vuex";
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
      flashMessage: null
    };
  },
  computed: {
    ...mapState("products", ["categories", "categoryProducts"])
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
    viewDetails(event) {
      event.preventDefault();
      this.$store.dispatch("openProductDetailsWindow", this.form.productId);
    },
    submit(event) {
      event.preventDefault();
      this.setNewProductInfo(this.form);
      this.addNewOrderProduct();
      this.resetFormData();
      this.flashMessage = { type: 'success', text: 'Product added successfully.' };
      this.clearFlashMessage();
    },
    resetFormData() {
      Object.assign(this.$data, this.$options.data.apply(this));
    },
    clearFlashMessage() {
      setTimeout(() => {
        this.flashMessage = null;
      }, 3000); // 3000 мілісекунд = 3 секунди
    }
  }
}
</script>

<style scoped>

</style>