require('./bootstrap');

import fullpage from 'z-fullpage';

import session from 'zoom-vue-session';
Vue.use(session);

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import {form} from 'z-ui';

var router = new VueRouter(require('./config/routes.login.js'));

const app = new Vue({
    el: '#app',
	components: {form, fullpae},
	router
}).$mount('#app');
