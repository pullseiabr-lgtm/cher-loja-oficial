import axios from 'axios'
import appService from "../../services/appService";

export const siteMenu = {
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
    },
    getters: {
        lists:      (state) => state.lists,
        pagination: (state) => state.pagination,
        page:       (state) => state.page,
        show:       (state) => state.show,
        temp:       (state) => state.temp,
    },
    actions: {
        lists: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = 'admin/site-menu';
                if (payload) url = url + appService.requestHandler(payload);
                axios.get(url).then((res) => {
                    if (typeof payload?.vuex === 'undefined' || payload?.vuex === true) {
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
                let url = '/admin/site-menu';
                if (context.state.temp.isEditing) {
                    url = `/admin/site-menu/${context.state.temp.temp_id}`;
                }
                method(url, payload.form).then((res) => {
                    context.dispatch('lists', payload.search).then().catch();
                    context.commit('reset');
                    resolve(res);
                }).catch(reject);
            });
        },
        edit:    (context, payload) => context.commit('temp', payload),
        reset:   (context)          => context.commit('reset'),
        destroy: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.delete(`admin/site-menu/${payload.id}`).then((res) => {
                    context.dispatch('lists', payload.search).then().catch();
                    resolve(res);
                }).catch(reject);
            });
        },
        show: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get(`admin/site-menu/show/${payload}`).then((res) => {
                    context.commit('show', res.data.data);
                    resolve(res);
                }).catch(reject);
            });
        },
    },
    mutations: {
        lists:      (state, payload) => { state.lists = payload; },
        pagination: (state, payload) => { state.pagination = payload; },
        page: function (state, payload) {
            if (payload) {
                state.page = { from: payload.from, to: payload.to, total: payload.total };
            }
        },
        show:  (state, payload) => { state.show = payload; },
        temp:  (state, payload) => { state.temp.temp_id = payload; state.temp.isEditing = true; },
        reset: (state)          => { state.temp.temp_id = null; state.temp.isEditing = false; },
    },
}
