import axios from 'axios';
import {StatusCodes} from "http-status-codes";
import  {HEADERS, HEADERS_PATCH} from '../../utils/config';
import {concatUrlByParams} from "../../utils/url-generator";

const state = {
    cart: {},
    isSentForm: false,
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
            assetImageProducts: window.staticStore.urlAssetImageProducts
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
            }
        } catch (error) {
            console.error(`Error: ${error.message}`);
            commit('setAlert', {type: 'warning', message: error.message})

        }
    },
    async clearCart({state, commit}) {
        const url = concatUrlByParams(
            state.staticStore.url.apiCart,
            state.cart.id
        );

        try {
            const response = await axios.delete(url, HEADERS);
            if (response.status === StatusCodes.NO_CONTENT) {
                commit('setCart', {});
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
    async updateCartProductQuantity({state, dispatch, commit}, payload ) {

        const url = concatUrlByParams(
            state.staticStore.url.apiCartProduct,
            payload.cartProductId
        );

        const data = {
            quantity: payload.quantity
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
    async makeOrder({state, commit, dispatch}) {
        const url = state.staticStore.url.apiOrder;
        const data = {
            cartId: state.cart.id
        };

        const result = await axios.post(url, data, HEADERS);

        if(result.data && result.status === StatusCodes.CREATED) {
            commit('setAlert', {
                type: 'success',
                message: 'Thank you for your purchase!'
            });
            commit('setIsSentForm', true);
            dispatch('clearCart');
        }
    },
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
    setIsSentForm(state, value) {
        state.isSentForm = value;
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}