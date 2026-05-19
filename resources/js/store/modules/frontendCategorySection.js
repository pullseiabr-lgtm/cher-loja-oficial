import axios from 'axios'

export const frontendCategorySection = {
    namespaced: true,
    state: {
        sections: [],
    },
    getters: {
        sections: function (state) {
            return state.sections;
        },
    },
    actions: {
        sections: function (context) {
            return new Promise((resolve, reject) => {
                axios.get('frontend/category-section/categories').then((res) => {
                    context.commit('sections', res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
    },
    mutations: {
        sections: function (state, payload) {
            state.sections = payload;
        },
    },
}
