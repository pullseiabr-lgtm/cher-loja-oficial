import axios from "axios";

export const emailTemplate = {
    namespaced: true,
    state: {
        lists: [],
        show: {},
    },
    getters: {
        lists: (state) => state.lists,
        show: (state) => state.show,
    },
    actions: {
        lists(context) {
            return new Promise((resolve, reject) => {
                axios.get("admin/setting/email-template")
                    .then((res) => {
                        context.commit("lists", res.data.data);
                        resolve(res);
                    })
                    .catch(reject);
            });
        },
        show(context, id) {
            return new Promise((resolve, reject) => {
                axios.get(`admin/setting/email-template/${id}`)
                    .then((res) => {
                        context.commit("show", res.data.data);
                        resolve(res);
                    })
                    .catch(reject);
            });
        },
        save(context, payload) {
            return new Promise((resolve, reject) => {
                axios.put(`admin/setting/email-template/${payload.id}`, payload)
                    .then((res) => {
                        resolve(res);
                    })
                    .catch(reject);
            });
        },
    },
    mutations: {
        lists(state, payload) {
            state.lists = payload;
        },
        show(state, payload) {
            state.show = payload;
        },
    },
};
