require('./bootstrap');

var vueRouter = require('vue-router');
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import sidebar from 'z-vue-sidebar';
Vue.use(sidebar);

import {session, clearSession} from 'zoom-vue-session';
Vue.use(session);

import {table, form, heading, statusBar, link, tab} from 'z-ui';
Vue.use(table);
Vue.use(form);
Vue.use(heading);
Vue.use(statusBar);
Vue.use(link);
Vue.use(tab);

import routes from './config/routes.app.js';
const router = new VueRouter({routes});

const app = new Vue({
	mixins: [clearSession],
	data: {
		entries: require('./config/entry.app.js'),
		entryfooter: require('./config/entryfooter.app.js')
	},
	router,
}).$mount('#app');
