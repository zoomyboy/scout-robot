require('./bootstrap');

var vueRouter = require('vue-router');
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import sidebar from 'z-vue-sidebar';
Vue.use(sidebar);

import {session, clearSession} from 'zoom-vue-session';
Vue.use(session);

window.globalFormOptions = {
	submitLabel: 'Absenden',
	texts: {
		sending: false,
		unauthorized: 'Du bist nicht autorisiert das zu tun.',
		notfound: 'Diese Resource wurde nicht gefunden.'
	}
};

import {table, form, heading, statusBar, link, tab, dropdown} from 'z-ui';
Vue.use(table);
Vue.use(form);
Vue.use(heading);
Vue.use(statusBar);
Vue.use(link);
Vue.use(tab);
Vue.use(dropdown);

import routes from './config/routes.app.js';
const router = new VueRouter({routes});

const app = new Vue({
	mixins: [clearSession],
	data: {
		entries: require('./config/entry.app.js'),
		entryfooter: require('./config/entryfooter.app.js'),
		$user: false
	},
	router
});

var Bootloader = require('./bootloader.js');
Vue.use(Bootloader, {
	mount: app,
	to: '#app'
});

window.app = app;


