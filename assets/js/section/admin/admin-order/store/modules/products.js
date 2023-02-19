import {concatUrlByParams} from "../../utils/url-generator";
import axios from 'axios';
import {StatusCodes} from "http-status-codes";
import  {HEADERS} from '../../utils/config';

const state = {
    categories: [],
    staticStore: {
        orderIs: window.staticStore.orderId,
        orderProducts: window.staticStore.orderProducts,

        url: {
            view: window.staticStore.urlViewProduct,
            apiOrderProduct: window.staticStore.urlApiOrderProduct,
            apiCategory: window.staticStore.urlApiCategory
        }
    }
};

const getters = {
};

const actions = {
    async getCategories({commit, state}) {
        const url = state.staticStore.url.apiCategory;
        try {
            const response = await axios.get(url, {
                headers: HEADERS
            });
            if (response.data && response.status === StatusCodes.OK) {
                commit('setCategories', response.data);
            }
        } catch (error) {
            console.error(`Error: ${error.message}`);
        }
        console.log(url)
    },
    async removeOrderProduct({state, dispatch}, orderProductId) {
        const url = concatUrlByParams(state.staticStore.url.apiOrderProduct, orderProductId);
        try {
            const response = await axios.delete(url, {
                headers: HEADERS
            });
            if (response.status === StatusCodes.NO_CONTENT) {
                console.log('Deleted successfully');
            }
        } catch (error) {
            console.error(`Failed to remove product from order: ${error.message}`);
        }
    }
};

const mutations = {
    setCategories(state, categories) {
        state.categories = categories;
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}