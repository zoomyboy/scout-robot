const heading = require('z-ui/heading/appheading.vue');

const memberIndex = () => import('../components/member/index.vue');
const memberAdd = () => import('../components/member/add.vue');
const memberEdit = () => import('../components/member/edit.vue');
const configIndex = () => import('../components/config/index.vue');
const profileIndex = () => import('../components/profile/index.vue');
const profilePassword = () => import('../components/profile/password.vue');

module.exports = [
	{
		path: '/member',
		name: 'member.index',
		props: { heading: { title: 'Mitglieder-Übersicht' } },
		components: { default: memberIndex, heading }
	},
	{
		path: '/member/add',
		name: 'member.add',
		props: { heading: { title: 'Miglied hinzufügen' } },
		components: { default: memberAdd, heading }
	},
	{
		path: '/member/edit/:id',
		name: 'member.edit',
		props: { heading: { title: 'Miglied bearbeiten' } },
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
	}
];
