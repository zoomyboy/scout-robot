// var heading = require('z-ui/heading/appheading.vue');

module.exports = [
{
	path: '/login',
	name: 'login.index',
	component: require('../components/login/login.vue')
},
// 	{
// 		path: '/password/reset',
// 		name: 'newpw',
// 		props: {
// 			heading: {
// 				title: 'Neues Passwort anfordern',
// 				line: true,
// 				small: true
// 			}
// 		},
// 		components: {
// 			default: require('../components/login/new-password.vue'),
// 			heading
// 		}
// 	},
// 	{
// 		path: '/password/reset/:token',
// 		name: 'resetpw',
// 		props: {
// 			heading: {
// 				title: 'Passwort zurücksetzen',
// 				line: true,
// 				small: true
// 			}
// 		},
// 		components: {
// 			default: require('../components/login/reset-password.vue'),
// 			heading
// 		}
// 	},
// 	{
// 		path: '/first-password/:token',
// 		name: 'firstpw',
// 		props: {
// 			heading: {
// 				title: 'Passwort setzen',
// 				line: true,
// 				small: true
// 			}
// 		},
// 		components: {
// 			default: require('../components/login/first-password.vue'),
// 			heading
// 		}
// 	}
];
