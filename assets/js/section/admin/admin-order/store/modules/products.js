const state = {
    categories: [],
    staticStore: {
        orderIs: window.staticStore.orderId,
        orderProducts: window.staticStore.orderProducts,

        url: {
            view: window.staticStore.productViewUrl
        }
    }
};

const getters = {
};

const actions = {
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