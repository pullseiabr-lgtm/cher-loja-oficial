import axios from 'axios';

export const frontendTestimonial = {
    namespaced: true,
    state: {
        lists: [],
        setting: {
            testimonials_section_status: 5,
            testimonials_section_per_page: 3,
        },
    },
    getters: {
        lists: function (state) { return state.lists; },
        setting: function (state) { return state.setting; },
    },
    actions: {
        lists: function (context) {
            return new Promise((resolve, reject) => {
                axios.get('frontend/testimonial').then((res) => {
                    context.commit('lists', res.data.data);
                    resolve(res);
                }).catch(reject);
            });
        },
        setting: function (context) {
            return new Promise((resolve, reject) => {
                axios.get('frontend/testimonial/setting').then((res) => {
                    context.commit('setting', res.data.data);
                    resolve(res);
                }).catch(reject);
            });
        },
    },
    mutations: {
        lists: function (state, payload) { state.lists = payload; },
        setting: function (state, payload) {
            if (payload) { state.setting = payload; }
        },
    },
};
