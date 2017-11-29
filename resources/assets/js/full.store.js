import Vuex from 'vuex';
Vue.use(Vuex);

import merge from 'merge';

const Store = new Vuex.Store({
	state: {
		user: false,
		config: false,
		actions: [],
		tables: {
			payment: {
				adding: false
			}
		}
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
		},
		tableadd(state, table) {
			state.tables[table].adding = true;	
		},
		addtable(state, name) {
			state.tables[name] = {
				adding: false
			};
		}
	},
	getters: {
		gettable: (state, table) => {
			if(state.tables) {
				return state.tables[table];
			} else {
				return false;
			}
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
