var heading = require('z-ui/heading/appheading.vue');

module.exports = [
	{
		path: '/login',
		name: 'login.index',
		props: {
			heading: {
				title: 'Einloggen',
				line: true,
				small: true
			}
		},
		components: {
			default: require('../components/login/login.vue'),
			heading
		}
	},
	{path: '/new-password', component: require('../components/login/new-password.vue'), name: 'newpw'},
	{path: '/reset-password/:token', component: require('../components/login/reset-password.vue'), name: 'resetpw'},
	{path: '/first-password/:token', component: require('../components/login/first-password.vue'), name: 'firstpw'}
];
