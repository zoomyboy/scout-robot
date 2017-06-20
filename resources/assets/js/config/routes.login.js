module.exports = [
	{path: '/', component: require('../components/login/login.vue'), name: 'login'},
	{path: '/new-password', component: require('../components/login/new-password.vue'), name: 'newpw'},
	{path: '/reset-password/:token', component: require('../components/login/reset-password.vue'), name: 'resetpw'}
];
