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
            apiOrderProduct: window.staticStore.urlApiOrderProduct
        }
    }
};

const getters = {
};

const actions = {
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

};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}