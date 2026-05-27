import axios from 'axios';
import appService from '../../services/appService';

export const testimonial = {
    namespaced: true,
    state: {
        lists: [],
        page: {},
        pagination: [],
        show: {},
        temp: {
            temp_id: null,
            isEditing: false,
        },
        setting: {
            testimonials_section_status: 5,
            testimonials_section_per_page: 3,
        },
    },
    getters: {
        lists: function (state) { return state.lists; },
        pagination: function (state) { return state.pagination; },
        page: function (state) { return state.page; },
        show: function (state) { return state.show; },
        temp: function (state) { return state.temp; },
        setting: function (state) { return state.setting; },
    },
    actions: {
        lists: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = 'admin/testimonial';
                if (payload) { url = url + appService.requestHandler(payload); }
                axios.get(url).then((res) => {
                    if (typeof payload.vuex === 'undefined' || payload.vuex === true) {
                        context.commit('lists', res.data.data);
                        context.commit('page', res.data.meta);
                        context.commit('pagination', res.data);
                    }
                    resolve(res);
                }).catch(reject);
            });
        },
        save: function (context, payload) {
            return new Promise((resolve, reject) => {
                let method = axios.post;
                let url = '/admin/testimonial';
                if (this.state['testimonial'].temp.isEditing) {
                    method = axios.post;
                    url = `/admin/testimonial/${this.state['testimonial'].temp.temp_id}`;
                }
                method(url, payload.form).then((res) => {
                    context.dispatch('lists', payload.search).then().catch();
                    context.commit('reset');
                    resolve(res);
                }).catch(reject);
            });
        },
        edit: function (context, payload) {
            context.commit('temp', payload);
        },
        destroy: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.delete(`admin/testimonial/${payload.id}`).then((res) => {
                    context.dispatch('lists', payload.search).then().catch();
                    resolve(res);
                }).catch(reject);
            });
        },
        show: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get(`admin/testimonial/show/${payload}`).then((res) => {
                    context.commit('show', res.data.data);
                    resolve(res);
                }).catch(reject);
            });
        },
        reset: function (context) {
            context.commit('reset');
        },
        getSetting: function (context) {
            return new Promise((resolve, reject) => {
                axios.get('admin/testimonial/setting/config').then((res) => {
                    context.commit('setting', res.data.data);
                    resolve(res);
                }).catch(reject);
            });
        },
        updateSetting: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.post('admin/testimonial/setting/config', payload).then((res) => {
                    context.commit('setting', res.data.data);
                    resolve(res);
                }).catch(reject);
            });
        },
    },
    mutations: {
        lists: function (state, payload) { state.lists = payload; },
        pagination: function (state, payload) { state.pagination = payload; },
        page: function (state, payload) {
            if (typeof payload !== 'undefined' && payload !== null) {
                state.page = { from: payload.from, to: payload.to, total: payload.total };
            }
        },
        show: function (state, payload) { state.show = payload; },
        temp: function (state, payload) {
            state.temp.temp_id = payload;
            state.temp.isEditing = true;
        },
        reset: function (state) {
            state.temp.temp_id = null;
            state.temp.isEditing = false;
        },
        setting: function (state, payload) {
            if (payload) { state.setting = payload; }
        },
    },
};
