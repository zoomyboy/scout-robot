import memberIndex from '../components/member/index.vue';
import memberAdd from '../components/member/add.vue';
import memberEdit from '../components/member/edit.vue';
import configIndex from '../components/config/index.vue';
import profileIndex from '../components/profile/index.vue';
import profilePassword from '../components/profile/password.vue';
import welcome from '../components/welcome/index.vue';
import mass from '../components/mass/index.vue';
import namiGet from '../components/nami/get.vue';
import subscriptionIndex from '../components/subscription/index.vue';
import createPayments from '../components/createpayments/index.vue';

export default [
{ path: '/', name: 'welcome', component: welcome },
{ path: '/member', name: 'member.index', component: memberIndex },
{ path: '/mass', name: 'mass.index', component: mass },
{ path: '/member/add', name: 'member.add', component: memberAdd },
{ path: '/member/:id/edit', name: 'member.edit', component: memberEdit },
{ path: '/config', name: 'config.index', component: configIndex },
{ path: '/profile', name: 'profile.index', component: profileIndex },
{ path: '/profile/password', name: 'profile.password', component: profilePassword },
{ path: '/nami', name: 'nami.get', component: namiGet },
{ path: '/subscription', name: 'subscription.index', component: subscriptionIndex },
{ path: '/createpayments', name: 'createpayments.index', component: createPayments }
];
