import { createStore } from 'vuex'
import cart from './modules/cart'
// import { getUrlViewProduct } from '../utils/url-generator'


const store = createStore({
    modules: {
        cart
    },
    actions: {
    }
})

export default store
