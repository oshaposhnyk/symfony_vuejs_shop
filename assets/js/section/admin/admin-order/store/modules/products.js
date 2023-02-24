import {concatUrlByParams, getUrlProductsByCategory} from "../../utils/url-generator";
import axios from 'axios';
import {StatusCodes} from "http-status-codes";
import  {HEADERS} from '../../utils/config';

const state = {
    categories: [],
    categoryProducts: [],
    orderProducts: [],
    busyProductsIds: [],
    newOrderProduct: {
        categoryId: "",
        productId: "",
        quantity: "",
        pricePerOne: ""
    },
    staticStore: {
        orderId: window.staticStore.orderId,
        orderProducts: window.staticStore.orderProducts,

        url: {
            view: window.staticStore.urlViewProduct,
            apiOrderProduct: window.staticStore.urlApiOrderProduct,
            apiCategory: window.staticStore.urlApiCategory,
            apiProduct: window.staticStore.urlApiProducts,
            apiOrder: window.staticStore.urlApiOrder
        }
    },
    viewProductCountLimit: 30
};

const getters = {
    freeCategoryProducts(state) {
        return state.categoryProducts.filter(
            item => state.busyProductsIds.indexOf(item.id) === -1
        );
    }
};

const actions = {
    async getOrderProducts({commit, state}) {
        const url = concatUrlByParams(
            state.staticStore.url.apiOrder,
             state.staticStore.orderId
        );

        try {
            const response = await axios.get(url, {
                headers: HEADERS
            });
            if (response.data && response.status === StatusCodes.OK) {
                console.log(response.data.orderProducts)
                commit('setOrderProducts', response.data.orderProducts);
                commit('setBusyProductsIds');
            }
        } catch (error) {
            console.error(`Error: ${error.message}`);
        }
    },
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
                dispatch('getOrderProducts');
            }
        } catch (error) {
            console.error(`Failed to remove product from order: ${error.message}`);
        }
    },
    async addNewOrderProduct({state, dispatch}) {
        const url = state.staticStore.url.apiOrderProduct;
        const data = {
            pricePerOne: ""+ state.newOrderProduct.pricePerOne,
            quantity: parseInt(state.newOrderProduct.quantity),
            product: "/api/products/" + state.newOrderProduct.productId,
            appOrder: "/api/orders/" + state.staticStore.orderId
        };

        try {
            const response = await axios.post(url, data, {
                headers: HEADERS
            });
            if (response.status === StatusCodes.CREATED) {
                dispatch('getOrderProducts');
            }
        } catch (error) {
            console.error(`Failed to create product from order: ${error.message}`);
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
    },
    setOrderProducts(state, orderProducts) {
        state.orderProducts = orderProducts;
    },
    setBusyProductsIds(state) {
        state.busyProductsIds = state.orderProducts.map(item => item.product.id);
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}