export default {
    state: {
        dialogs: []
    },
    actions: {
        confirm({commit}, message) {
            return new Promise((resolve, reject) => {
                commit('setDialog', {
                    'message': message,
                    'resolve': resolve,
                    'reject': reject
                });
            });
        }
    },
    getters: {
        dialogs: state => {
            return state.dialogs;
        }
    },
    mutations: {
        setDialog(state, payload) {
            state.dialogs.push(payload);
        }
    }
}
