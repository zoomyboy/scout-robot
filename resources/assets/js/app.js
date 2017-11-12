require('./bootstrap');

var vueRouter = require('vue-router');
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import sidebar from 'z-vue-sidebar';
Vue.use(sidebar);

import {session, clearSession} from 'zoom-vue-session';
Vue.use(session);

Vue.component('vfForm', require('z-ui/form/form.vue'));
Vue.component('vfHidden', require('z-ui/form/fields/hidden.vue'));
Vue.component('vfText', require('z-ui/form/fields/text.vue'));
Vue.component('vfSubmit', require('z-ui/form/fields/submit.vue'));

window.globalFormOptions = {
	submitLabel: 'Absenden',
	texts: {
		sending: false,
		unauthorized: 'Du bist nicht autorisiert das zu tun.',
		notfound: 'Diese Resource wurde nicht gefunden.'
	}
};

let store = require('./full.store.js').default;

import routes from './route/app.js';
const router = new VueRouter({
	routes,
	mode: 'history'
});

import {mapState} from 'vuex';

const app = window.app = new Vue({
	mixins: [clearSession],
	data: {
		entries: require('./entry/app.js'),
		entryfooter: require('./entry/footer.js')
	},
	components: {
		appfooter: require('z-ui/footer.vue'),
		footerlink: require('z-vue-sidebar/footerlink.vue'),
		dropdown: require('z-ui/dropdown/dropdown.vue'),
		dropdownlink: require('z-ui/dropdown/dropdownlink.vue'),
		statusbar: require('z-ui/statusbar.vue'),
	},
	computed: mapState(['user']),
	router,
	store,
	created() {
		this.$store.dispatch('getinfo').then((data) => {
			this.$store.commit('setinfo', data);
		});
	}
})

app.$mount('#app');


