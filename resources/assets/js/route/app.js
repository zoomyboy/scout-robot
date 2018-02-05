// const heading = require('z-ui/heading/appheading.vue');
// 
const memberIndex = () => import('../components/member/index.vue');
const memberAdd = () => import('../components/member/add.vue');
// const memberEdit = () => import('../components/member/edit.vue');
const configIndex = () => import('../components/config/index.vue');
const profileIndex = () => import('../components/profile/index.vue');
const profilePassword = () => import('../components/profile/password.vue');
const welcome = () => import('../components/welcome/index.vue');
// const mass = () => import('../components/mass/index.vue');
const namiGet = () => import('../components/nami/get.vue');
const subscriptionIndex = () => import('../components/subscription/index.vue');
// const subscriptionAdd = () => import('../components/subscription/add.vue');
// const subscriptionEdit = () => import('../components/subscription/edit.vue');
// 
module.exports = [
{
    path: '/',
    name: 'welcome',
    component: welcome
},
{
    path: '/member',
    name: 'member.index',
    component: memberIndex
},
//  {
//      path: '/mass',
//      name: 'mass.index',
//      props: { heading: { title: 'Massenversand' } },
//      components: { default: mass, heading }
//  },
    {
        path: '/member/add',
        name: 'member.add',
        component: memberAdd
    },
//  {
//      path: '/member/:id/edit',
//      name: 'member.edit',
//      props: { heading: { title: 'Mitglied bearbeiten' } },
//      components: { default: memberEdit, heading }
//  },
{
    path: '/config',
    name: 'config.index',
    component: configIndex
},
{
    path: '/profile',
    name: 'profile.index',
    component: profileIndex
},
{
    path: '/profile/password',
    name: 'profile.password',
    component: profilePassword
},
//  {
//      path: '/user',
//      name: 'user.index',
//      props: { heading: { title: 'Benutzer-Übersicht' } },
//      components: { default: profilePassword, heading }
//  },
{
    path: '/nami',
    name: 'nami.get',
    component: namiGet,
},
    {
        path: '/subscription',
        name: 'subscription.index',
        component: subscriptionIndex
    },
//  {
//      path: '/subscription/add',
//      name: 'subscription.add',
//      props: { heading: { title: 'Beitrag hinzufügen' } },
//      components: { default: subscriptionAdd, heading }
//  },
//  {
//      path: '/subscription/:id',
//      name: 'subscription.edit',
//      props: { heading: { title: 'Beitrag bearbeiten' } },
//      components: { default: subscriptionEdit, heading }
//  }
];
