
require('./bootstrap');

var vueRouter = require('vue-router');
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import routes from './route/full.js';
const router = new VueRouter({
	routes,
	mode: 'history'
});

import Vuetify from 'vuetify'
Vue.use(Vuetify)
import 'vuetify/dist/vuetify.min.css'

import validator from './mixins/validator.js';
Vue.mixin(validator);

import Full from './Full.vue';

import Vuex from 'vuex';
Vue.use(Vuex);
const store = new Vuex.Store({
	state: {
		appname: '',
		notification: false,
		ready: false,
		apptitle: '',
		toolbar: [
			{href: 'https://zoomyboy.github.io/scout-robot/', title: 'Dokumentation'},
			{href: 'https://github.com/zoomyboy/scout-robot', icon: 'fa-github'}
		]
	},
	mutations: {
		setappname(store, appname) {
			Vue.set(store, 'appname', appname);
		},
		ready: function(store) {
			Vue.set(store, 'ready', true);
		},
		settitle: function(state, title) {
			state.apptitle = title;
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
		},
	}
});

const app = window.app = new Vue({
	el: '#app',
	router,
	store,
	render: (h) => h(Full)
})

