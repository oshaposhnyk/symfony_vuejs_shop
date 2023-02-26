import axios from 'axios';
import {StatusCodes} from "http-status-codes";
import  {HEADERS, HEADERS_PATCH} from '../../utils/config';
import {concatUrlByParams} from "../../utils/url-generator";

const state = {
    cart: {},
    alert: {
        type: null,
        message: null,
    },
    staticStore: {
        url: {
            apiCart: window.staticStore.urlCart,
            apiCartProduct: window.staticStore.urlCartProduct,
            apiOrder: window.staticStore.urlOrder,
            viewProduct: window.staticStore.urlViewProduct,
            assetImageProducts: window.staticStore.urlAssetImageProducts,
            urlCart: window.staticStore.urlViewCart
        }
    }
};

const getters = {
    totalPrice(state, ) {
        let result = 0;
        if (!state.cart.cartProducts) {
            return 0;
        }
        state.cart.cartProducts.forEach(
            cartProduct => {
                return result += cartProduct.product.price * cartProduct.quantity
            }
        )
        return result.toFixed(2);
    }
};

const actions = {
    async getCart({state, commit, dispatch}) {
        const url = state.staticStore.url.apiCart;

        try {
            const response = await axios.get(url, HEADERS);
            if (
                response.data &&
                response.data.length &&
                response.status === StatusCodes.OK
            ) {
                commit('setCart', response.data[0]);
            } else {
                dispatch("createCart");
            }

        } catch (error) {
            console.error(`Error: ${error.message}`);
            commit('setAlert', {type: 'warning', message: error.message})

        }
    },
    async clearCart({state, commit, dispatch}) {
        const url = concatUrlByParams(
            state.staticStore.url.apiCart,
            state.cart.id
        );

        try {
            const response = await axios.delete(url, HEADERS);
            if (response.status === StatusCodes.NO_CONTENT) {
                commit('setCart', {});
                dispatch('createCart');

            }
        } catch (error) {
            console.error(`Error: ${error.message}`);
            commit('setAlert', {type: 'warning', message: error.message})

        }
    },
    async removeCartProduct({state, dispatch, commit}, cartProductId) {
        const url = concatUrlByParams(
            state.staticStore.url.apiCartProduct,
            cartProductId
        );

        try {
            const response = await axios.delete(url, HEADERS);

            if (response.status === StatusCodes.NO_CONTENT) {
                dispatch("getCart");
                commit('setAlert', {type: 'success', message: 'OK'})
            }
        } catch (error) {
            console.error(`Error: ${error.message}`);
            commit('setAlert', {type: 'warning', message: error.message})
        }
    },
    addCartProduct({state, dispatch, commit}, productData) {
        const existCartProduct = state.cart.cartProducts.find(
            cartProduct => cartProduct.product.uuid === productData.uuid
        );

        if (existCartProduct) {
            dispatch("addExistCartProduct", existCartProduct);
        } else {
            dispatch("addNewCartProduct", productData);
        }
        console.log(state.cart.cartProducts, productData);
    },
    async addNewCartProduct({state, dispatch, commit}, productData) {
        const url = state.staticStore.url.apiCartProduct;
        const data = {
            cart: '/api/carts/' + state.cart.id,
            product: '/api/products/' + productData.uuid,
            quantity: 1
        };

        try {
            const response = await axios.post(url, data, HEADERS);
            if (response.data && response.status === StatusCodes.CREATED) {
                dispatch('getCart');
            }
        } catch (error) {
            console.error(`Error: ${error.message}`);
            commit('setAlert', {type: 'warning', message: error.message})

        }


    },
    async addExistCartProduct({state, dispatch, commit}, existCartProduct) {
        const url = concatUrlByParams(
            state.staticStore.url.apiCartProduct,
            existCartProduct.id
        );

        const data = {
            quantity: existCartProduct.quantity + 1
        };

        try {
            const response = await axios.patch(url, data, HEADERS_PATCH);

            if (response.status === StatusCodes.OK) {
                dispatch("getCart");
            }
        } catch (error) {
            console.error(`Error: ${error.message}`);
            commit('setAlert', {type: 'warning', message: error.message})
        }


    },
    async createCart({state, commit, dispatch}) {
        const url = state.staticStore.url.apiCart;
        const data = {};

        const result = await axios.post(url, data, HEADERS);
        console.log(result);


        if(result.data && result.status === StatusCodes.CREATED) {
            dispatch("getCart");
        }
    }


};

const mutations = {
    setCart(state, cart) {
        state.cart = cart;
    },
    cleanAlert(state) {
        state.alert = {
            type: null,
            message: null
        };
    },
    setAlert(state, model) {
        state.alert = {
            type: model.type,
            message: model.message,
        };
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}