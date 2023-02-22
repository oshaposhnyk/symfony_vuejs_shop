import {concatUrlByParams, getUrlProductsByCategory} from "../../utils/url-generator";
import axios from 'axios';
import {StatusCodes} from "http-status-codes";
import  {HEADERS} from '../../utils/config';

const state = {
    categories: [],
    categoryProducts: [],
    newOrderProduct: {
        categoryId: "",
        productId: "",
        quantity: "",
        pricePerOne: ""
    },
    staticStore: {
        orderIs: window.staticStore.orderId,
        orderProducts: window.staticStore.orderProducts,

        url: {
            view: window.staticStore.urlViewProduct,
            apiOrderProduct: window.staticStore.urlApiOrderProduct,
            apiCategory: window.staticStore.urlApiCategory,
            apiProduct: window.staticStore.urlApiProducts
        }
    },
    viewProductCountLimit: 30
};

const getters = {
};

const actions = {
    async getProductsByCategory({commit, state}) {
        //https://localhost:8000/api/products?page=1&itemsPerPage=30&isPublished=true&category=1
        const url = getUrlProductsByCategory(
            state.staticStore.url.apiProduct,
            state.newOrderProduct.categoryId,
            1,
            state.viewProductCountLimit
        );
        try {
            const response = await axios.get(url, {
                headers: HEADERS
            });
            if (response.data && response.status === StatusCodes.OK) {
                commit('setCategoryProducts', response.data);
            }
        } catch (error) {
            console.error(`Error: ${error.message}`);
        }
    },
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
    },
    setNewProductInfo(state, formData) {
        state.newOrderProduct.categoryId = formData.categoryId;
        state.newOrderProduct.productId = formData.productId;
        state.newOrderProduct.quantity = formData.quantity;
        state.newOrderProduct.pricePerOne = formData.pricePerOne;
    },
    setCategoryProducts(state, categoryProducts) {
        state.categoryProducts = categoryProducts;
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}