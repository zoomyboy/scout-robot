require('./bootstrap');

import Event from 'vue-events';
Vue.use(Event);

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import routes from './route/full.js';
const router = new VueRouter({
	routes,
	mode: "history"
});

Vue.component('vfForm', require('z-ui/form/form.vue'));
Vue.component('vfText', require('z-ui/form/fields/text.vue'));
Vue.component('vfPassword', require('z-ui/form/fields/password.vue'));
Vue.component('vfSubmit', require('z-ui/form/fields/submit.vue'));
Vue.component('vLink', require('z-ui/link/link.vue'));

const app = new Vue({
	components: {
		fullpage: require('z-fullpage/fullpage.vue'),
		statusbar: require('z-ui/statusbar.vue')
	},
	router
}).$mount('#app');
