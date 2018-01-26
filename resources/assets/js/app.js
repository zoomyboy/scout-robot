require('./bootstrap');

var vueRouter = require('vue-router');
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import routes from './route/app.js';
const router = new VueRouter({
	routes,
	mode: 'history'
});

import {mapState} from 'vuex';

import Vuex from 'vuex';
Vue.use(Vuex);

import Vuetify from 'vuetify'
Vue.use(Vuetify)
import 'vuetify/dist/vuetify.min.css'

import merge from 'merge';

import validator from './mixins/validator.js';
Vue.mixin(validator);

const store = new Vuex.Store({
	state: {
		user: false,
		actions: [],
		tables: {
			payment: {
				adding: false
			}
		},
		notification: false,
		toolbar: [
			{icon: 'more_vert', children: [
				{title: 'Mein Profil', route: 'profile.index', icon: 'fa-user'}
			]}
		],
		config: false,
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
		setappname: function(state, appname) {
			Vue.set(state.config, 'appname', 'aaaa');
			state.loaded = true;
		},
		errormsg: function(store, message, delay) {
			if (delay === undefined) {delay = 3000;}
			store.notification = {
				message,
				color: 'error'
			};
			window.setTimeout(function() {
				store.notification = false;
			}, delay);
		},
		successmsg: function(store, message, delay) {
			if (delay === undefined) {delay = 3000;}
			store.notification = {
				message,
				color: 'success'
			};
			window.setTimeout(function() {
				store.notification = false;
			}, delay);
		}
	},
	getters: {
		appname: function(state) {
			return (state.config.appname != undefined) ? state.config.appname : '';
		},
		loaded: function(state) {
			return state.user != false && state.config != false;
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

import App from './App.vue';

const app = window.app = new Vue({
	el: '#app',
	computed: mapState(['user']),
	router,
	store,
	render: (h) => h(App)
})

