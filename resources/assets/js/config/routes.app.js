module.exports = [
	{path: '/member', component: require('../components/member/index.vue'), name: 'member.index'},
	{path: '/config', component: require('../components/config/index.vue'), name: 'config.index'},
	{path: '/profile', component: require('../components/profile/index.vue'), name: 'profile.index'},
	{path: '/profile/password', component: require('../components/profile/password.vue'), name: 'profile.password'},
	{path: '/member/add', component: require('../components/member/add.vue'), name: 'member.add'},

	{path: '/usergroup', component: require('../components/usergroup/index.vue'), name: 'usergroup.index'},
	{path: '/usergroup/add', component: require('../components/usergroup/add.vue'), name: 'usergroup.add'},
	{path: '/usergroup/:id/edit', component: require('../components/usergroup/edit.vue'), name: 'usergroup.edit'},
];
