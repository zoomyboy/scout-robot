require('./bootstrap');

import fullpage from 'z-fullpage';
Vue.use(fullpage);

import Event from 'vue-events';
Vue.use(Event);

import {session, clearSession} from 'zoom-vue-session';
Vue.use(session);

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import {form, heading, link, statusbar} from 'z-ui';
Vue.use(form);
Vue.use(heading);
Vue.use(link);
Vue.use(statusbar);

import routes from './config/routes.login.js';
const router = new VueRouter({
	routes
});

const app = new Vue({
	mixins: [clearSession],
	router
}).$mount('#app');
