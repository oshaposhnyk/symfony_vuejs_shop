<template>
  <tr>
    <td>
      {{cartProduct.product.id}}
    </td>
    <td class="product-col">
      <div class="text-center">
        <figure>
          <a
              :href="urlShowProduct"
              target="_blank"
          >
            <v-img
                :lazy-src="getUrlProductImage(productImage)"
                max-height="80"
                :src="getUrlProductImage(productImage)"
            ></v-img>
          </a>
        </figure>
        <div class="product-title">
          <a
              :href="urlShowProduct"
              target="_blank"
          >
            {{cartProduct.product.title}}
          </a>
        </div>
      </div>
    </td>
    <td class="price-col">
      ${{cartProduct.product.price}}
    </td>
    <td class="quantity-col">
      <input
          v-model="quantity"
          type="number"
          class="form-control"
          min="1"
          step="1"
      >
    </td>
    <td class="total-col">
      ${{productPrice}}
    </td>
    <td class="remove-col">
      <v-btn
          variant="flat"
          color="error"
          @click="removeCartProduct(cartProduct.id)"
      >
        Remove
      </v-btn>
    </td>
  </tr>
</template>

<script>
import {mapActions, mapState} from "vuex";

export default {
  data() {
    return {
      quantity: 1,
    }
  },
  name: "CardProductItem",
  props: {
    cartProduct: {
      type: Object,
      required: true,
    },
    key: {
      type: Number,
      default: 0
    }
  },
  computed: {
    ...mapState("cart", ["staticStore"]),
    productImage() {
      const productImages = this.cartProduct.product.productImages;
      return productImages.length ? productImages[0] : null;
    },
    productPrice() {
      return this.quantity * this.cartProduct.product.price;
    },
    urlShowProduct() {
      return this.staticStore.url.viewProduct + "/" + this.cartProduct.product.uuid;
    }
  },
  methods: {
    ...mapActions("cart", ["removeCartProduct"]),
    getUrlProductImage(productImage) {
      return (
          this.staticStore.url.assetImageProducts +
              "/" +
              this.cartProduct.product.id +
              "/" +
              productImage.filenameSmall
      );
    }
  }
}
</script>

<style scoped>

</style>