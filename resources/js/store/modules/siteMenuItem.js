import axios from 'axios'

export const siteMenuItem = {
    namespaced: true,
    state: {
        lists: [],
        temp: {
            temp_id:   null,
            menu_id:   null,
            isEditing: false,
        },
    },
    getters: {
        lists: (state) => state.lists,
        temp:  (state) => state.temp,
    },
    actions: {
        lists: function (context, menuId) {
            return new Promise((resolve, reject) => {
                axios.get(`admin/site-menu/${menuId}/item`).then((res) => {
                    context.commit('lists', res.data.data);
                    resolve(res);
                }).catch(reject);
            });
        },
        save: function (context, payload) {
            return new Promise((resolve, reject) => {
                const menuId = payload.menu_id;
                let url    = `/admin/site-menu/${menuId}/item`;
                let method = axios.post;
                if (context.state.temp.isEditing) {
                    url    = `/admin/site-menu/${menuId}/item/${context.state.temp.temp_id}`;
                }
                method(url, payload.form).then((res) => {
                    context.dispatch('lists', menuId).then().catch();
                    context.commit('reset');
                    resolve(res);
                }).catch(reject);
            });
        },
        edit:    (context, payload) => context.commit('temp', payload),
        reset:   (context)          => context.commit('reset'),
        destroy: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.delete(`admin/site-menu/${payload.menu_id}/item/${payload.id}`).then((res) => {
                    context.dispatch('lists', payload.menu_id).then().catch();
                    resolve(res);
                }).catch(reject);
            });
        },
        moveUp: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.post(`admin/site-menu/${payload.menu_id}/item/${payload.id}/move-up`).then((res) => {
                    context.dispatch('lists', payload.menu_id).then().catch();
                    resolve(res);
                }).catch(reject);
            });
        },
        moveDown: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.post(`admin/site-menu/${payload.menu_id}/item/${payload.id}/move-down`).then((res) => {
                    context.dispatch('lists', payload.menu_id).then().catch();
                    resolve(res);
                }).catch(reject);
            });
        },
    },
    mutations: {
        lists: (state, payload) => { state.lists = payload; },
        temp:  (state, payload) => { state.temp.temp_id = payload.id; state.temp.menu_id = payload.menu_id; state.temp.isEditing = true; },
        reset: (state)          => { state.temp.temp_id = null; state.temp.menu_id = null; state.temp.isEditing = false; },
    },
}
