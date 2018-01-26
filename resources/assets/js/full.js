require('./bootstrap');

import Event from 'vue-events';
Vue.use(Event);

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import routes from './route/full.js';

import {session, clearSession} from 'zoom-vue-session';
Vue.use(session);

const router = new VueRouter({
	routes,
	mode: "history"
});

const app = new Vue({
	mixins: [clearSession],
	components: {
	},
	router
}).$mount('#app');
