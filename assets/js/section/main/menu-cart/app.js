import { createApp } from 'vue';
import App from "./App.vue";
import store from './store/store';

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const vueMenuCartInstance = createApp(App);
const vuetify = createVuetify({
    components,
    directives,
})


vueMenuCartInstance.use(vuetify);
vueMenuCartInstance.use(store);

vueMenuCartInstance.mount('#appMainMenuCart');

window.vueMenuCartInstance = {};
window.vueMenuCartInstance.addCartProduct =
    (productData) => store.dispatch('cart/addCartProduct', productData);