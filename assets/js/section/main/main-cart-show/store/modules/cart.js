import axios from 'axios';
import {StatusCodes} from "http-status-codes";
import  {HEADERS} from '../../utils/config';
import {concatUrlByParams} from "../../utils/url-generator";

const state = {
    cart: {},
    staticStore: {
        url: {
            apiCart: window.staticStore.urlCart,
            apiCartProduct: window.staticStore.urlCartProduct,
            viewProduct: window.staticStore.urlViewProduct,
            assetImageProducts: window.staticStore.urlAssetImageProducts
        }
    }
};

const getters = {
};

const actions = {
    async getCart({state, commit}) {
        const url = state.staticStore.url.apiCart;

        try {
            const response = await axios.get(url, HEADERS);

            if (response.data && response.status === StatusCodes.OK) {
                commit('setCart', response.data[0]);
            }
        } catch (error) {
            console.error(`Error: ${error.message}`);
        }
    },
    async removeCartProduct({state, dispatch}, cartProductId) {
        const url = concatUrlByParams(
            state.staticStore.url.apiCartProduct,
            cartProductId
        );

        try {
            const response = await axios.delete(url, HEADERS);

            if (response.status === StatusCodes.NO_CONTENT) {
                dispatch("getCart");
            }
        } catch (error) {
            console.error(`Error: ${error.message}`);
        }
    }
};

const mutations = {
    setCart(state, cart) {
        state.cart = cart;
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}