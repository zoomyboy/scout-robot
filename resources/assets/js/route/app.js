const heading = require('z-ui/heading/appheading.vue');

const memberIndex = () => import('../components/member/index.vue');
const memberAdd = () => import('../components/member/add.vue');
const memberEdit = () => import('../components/member/edit.vue');
const configIndex = () => import('../components/config/index.vue');
const profileIndex = () => import('../components/profile/index.vue');
const profilePassword = () => import('../components/profile/password.vue');
const welcome = () => import('../components/welcome/index.vue');
const mass = () => import('../components/mass/index.vue');
const namiGet = () => import('../components/nami/get.vue');
const subscriptionIndex = () => import('../components/subscription/index.vue');
const subscriptionAdd = () => import('../components/subscription/add.vue');
const subscriptionEdit = () => import('../components/subscription/edit.vue');

module.exports = [
	{
		path: '/',
		name: 'welcome',
		props: { heading: { title: 'Willkommen' } },
		components: { default: welcome, heading }
	},
	{
		path: '/member',
		name: 'member.index',
		props: { heading: { title: 'Mitglieder-Übersicht' } },
		components: { default: memberIndex, heading }
	},
	{
		path: '/mass',
		name: 'mass.index',
		props: { heading: { title: 'Massenversand' } },
		components: { default: mass, heading }
	},
	{
		path: '/member/add',
		name: 'member.add',
		props: { heading: { title: 'Mitglied hinzufügen' } },
		components: { default: memberAdd, heading }
	},
	{
		path: '/member/:id/edit',
		name: 'member.edit',
		props: { heading: { title: 'Mitglied bearbeiten' } },
		components: { default: memberEdit, heading }
	},
	{
		path: '/config',
		name: 'config.index',
		props: { heading: { title: 'Konfiguration' } },
		components: { default: configIndex, heading }
	},
	{
		path: '/profile',
		name: 'profile.index',
		props: { heading: { title: 'Eigenes Benutzerkonto' } },
		components: { default: profileIndex, heading }
	},
	{
		path: '/profile/password',
		name: 'profile.password',
		props: { heading: { title: 'Eigenes Passwort' } },
		components: { default: profilePassword, heading }
	},
	{
		path: '/user',
		name: 'user.index',
		props: { heading: { title: 'Benutzer-Übersicht' } },
		components: { default: profilePassword, heading }
	},
	{
		path: '/nami',
		name: 'nami.index',
		props: { heading: { title: 'NaMi-Abruf' } },
		components: { default: namiGet, heading }
	},
	{
		path: '/subscription',
		name: 'subscription.index',
		props: { heading: { title: 'Beitrags-Übersicht' } },
		components: { default: subscriptionIndex, heading }
	},
	{
		path: '/subscription/add',
		name: 'subscription.add',
		props: { heading: { title: 'Beitrag hinzufügen' } },
		components: { default: subscriptionAdd, heading }
	},
	{
		path: '/subscription/:id',
		name: 'subscription.edit',
		props: { heading: { title: 'Beitrag bearbeiten' } },
		components: { default: subscriptionEdit, heading }
	}
];
