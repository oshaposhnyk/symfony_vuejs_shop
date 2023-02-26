<template>
  <div class="product">
    <div class="product-details">
      <h6 class="product-title">
        <a
            :href="urlShowProduct"
            target="_blank"
        >{{cartProduct.product.title}}</a>
      </h6>
      <span class="product-info">
        <span class="product-quantity">
          {{cartProduct.quantity}}
        </span>
        x ${{cartProduct.product.price}}
      </span>
    </div>
    <figure class="product-image-container mr-2">
      <a
          :href="urlShowProduct"
          target="_blank"
      >
        <img
            :src="getUrlProductImage(productImage)"
            class="product-image"
            :alt="cartProduct.product.title">
      </a>
    </figure>
    <v-btn
        variant="flat"
        color="error"
        @click="removeCartProduct(cartProduct.id)"
    >
      <i class="fa fa-trash"></i>
    </v-btn>
  </div>
</template>

<script>
import {mapActions, mapState} from "vuex";

export default {
  name: "CartProductItem",
  props: {
    cartProduct: {
      type: Object,
      required: true,
      validator: function(obj) {
        return (obj.id && obj.product);
      }
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
    urlShowProduct() {
      return this.staticStore.url.viewProduct + this.cartProduct.product.uuid;
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
    },
    updateQuantity(cartProductId, quantity) {
      this.updateCartProductQuantity({cartProductId, quantity});
    }
  }
}
</script>

<style scoped>

</style>