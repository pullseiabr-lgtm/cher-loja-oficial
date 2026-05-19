import axios from 'axios'

export const frontendSiteMenu = {
    namespaced: true,
    state: {
        header: [],
        footer: [],
    },
    getters: {
        header: (state) => state.header,
        footer: (state) => state.footer,
    },
    actions: {
        header: function (context) {
            return new Promise((resolve, reject) => {
                axios.get('frontend/site-menu/header').then((res) => {
                    context.commit('header', res.data.data || []);
                    resolve(res);
                }).catch(reject);
            });
        },
        footer: function (context) {
            return new Promise((resolve, reject) => {
                axios.get('frontend/site-menu/footer').then((res) => {
                    context.commit('footer', res.data.data || []);
                    resolve(res);
                }).catch(reject);
            });
        },
    },
    mutations: {
        header: (state, payload) => { state.header = payload; },
        footer: (state, payload) => { state.footer = payload; },
    },
}
