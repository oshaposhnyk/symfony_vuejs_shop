<template>
  <div class="row mb-2">
    <div class="col-md-2">
      <select
        v-model="form.categoryId"
        name="add_product_category_select"
        class="form-control"
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
          name="add_product_product_select"
          class="form-control"
      >
        <option value="" disabled>- choose options -</option>
      </select>
    </div>
    <div class="col-md-2">
      <input
          v-model="form.quantity"
          type="number"
          class="form-control"
          placeholder="quantity"
          min="0"
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
      />
    </div>
    <div class="col-md-2 d-flex justify-content-between">
      <button class="btn btn-info btn-sm">Details</button>
      <button class="btn btn-success btn-sm">Add</button>
    </div>
  </div>
</template>

<script>
import {mapState} from "vuex";

export default {
  name: "OrderProductAdd",
  data() {
    return {
      form: {
        categoryId: "",
        productId: "",
        quantity: "",
        pricePerOne: ""
      }
    };
  },
  computed: {
    ...mapState("products", ["categories"])
  }
}
</script>

<style scoped>

</style>