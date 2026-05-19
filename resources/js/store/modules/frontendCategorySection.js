import axios from 'axios'

export const frontendCategorySection = {
    namespaced: true,
    state: {
        lists: [],
    },
    getters: {
        lists: function (state) {
            return state.lists;
        },
    },
    actions: {
        lists: function (context) {
            return new Promise((resolve, reject) => {
                axios.get('frontend/category-section/categories').then((res) => {
                    context.commit('lists', res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
    },
    mutations: {
        lists: function (state, payload) {
            state.lists = payload;
        },
    },
}
