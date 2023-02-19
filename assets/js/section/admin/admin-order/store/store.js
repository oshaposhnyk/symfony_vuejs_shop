import { createStore } from 'vuex'
import products from './modules/products'
import { getUrlViewProduct } from '../utils/url-generator'


const store = createStore({
    modules: {
        products
    },
    actions: {
        openProductDetailsWindow({ state }, productId) {
            const url = getUrlViewProduct(state.products.staticStore.url.view, productId);
            window.open(url, '_blank').focus();
        }
    }
})

export default store
