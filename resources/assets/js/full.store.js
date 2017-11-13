import Vuex from 'vuex';
Vue.use(Vuex);

import merge from 'merge';

const Store = new Vuex.Store({
	state: {
		user: false,
		config: false,
		actions: []
	},
	mutations: {
		setinfo(state, data) {
			state.user = data.user;
			state.config = data.conf;
		},
		updateuser(state, data) {
			state.user = merge(state.user, data);
		},
		updateconfig(state, data) {
			state.config = data;
		},
		setactions(actions, data) {
			state.actions = data;
		}
	},
	actions: {
		getinfo() {
			return new Promise((resolve, reject) => {
				axios.get('/api/info').then((ret) => {
					resolve(ret.data);
				}).catch((error) => {
					reject(error);
				});
			});
		}
	}
});

export default Store;
