import axios from 'axios';
import {StatusCodes} from "http-status-codes";

const state = {
    cart: {},
    alert: {
        type: null,
        message: null,
    },
    staticStore: {
        url: {
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