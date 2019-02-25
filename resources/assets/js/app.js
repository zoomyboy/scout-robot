require('./bootstrap');

var vueRouter = require('vue-router');
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import routes from './route/app.js';
const router = new VueRouter({
    routes,
    mode: 'history'
});

import {mapState} from 'vuex';

import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)

import Vuex from 'vuex';
Vue.use(Vuex);

import Vuetify from 'vuetify'
Vue.use(Vuetify)
import 'vuetify/dist/vuetify.min.css'

import merge from 'merge';

import validator from './mixins/validator.js';
Vue.mixin(validator);

import echo from './mixins/echo.js';
Vue.mixin(echo);

import progress from './vuex/progress.js';
import confirm from './vuex/confirm.js';
import member from './vuex/member.js';

const store = new Vuex.Store({
    modules: { progress, confirm, member },
    state: {
        user: false,
        actions: [],
        tables: {
            payment: {
                adding: false
            }
        },
        navvisible: true,
        notification: false,
        toolbar: [
            {icon: 'more_vert', children: [
                {title: 'Mein Profil', route: 'profile.index', icon: 'fa-user'},
                {title: 'Passwort ändern', route: 'profile.password', icon: 'fa-key'}
            ]}
        ],
        navbar: [
            {icon: 'fa-users', title: 'Mitglieder', route: 'member.index'},
            {icon: 'fa-money', title: 'Beiträge', route: 'subscription.index'},
            {icon: 'fa-envelope-o', title: 'Massenversand', route: 'mass.index'},
            {icon: 'fa-refresh', title: 'NaMi-Abruf', route: 'nami.get'},
            {icon: 'fa-money', title: 'Zahlungen erstellen', route: 'createpayments.index'},
            {icon: 'fa-cog', title: 'Konfiguration', route: 'config.index'},
        ],
        config: {},
        apptitle: '',
        fees: false,
        countries: false,
        regions: false,
        nationalities: false,
        subscriptions: false,
        timeunits: false,
        statuses: false
    },
    mutations: {
        setinfo(state, data) {
            state.user = data.user;
            state.config = data.conf;
            state.regions = data.regions;
            state.countries = data.countries;
            state.fees = data.fees;
            state.nationalities = data.nationalities;
            state.timeunits = data.timeunits;
            state.genders = data.genders;
            state.ways = data.ways;
            state.confessions = data.confessions;
            state.activities = data.activities;
            state.subscriptions = data.subscriptions;
            state.statuses = data.statuses;
            this.commit('setappname', data.app.name);
        },
        updateuser(state, data) {
            state.user = merge(state.user, data);
        },
        updateconfig(state, data) {
            state.config = data;
        },
        setactions(actions, data) {
            state.actions = data;
        },
        settitle: function(state, title) {
            state.apptitle = title;
        },
        setsubscriptions: function(state, sub) {
            Vue.set(state, 'subscriptions', sub);
        },
        setappname: function(state, appname) {
            Vue.set(state.config, 'appname', appname);
            state.loaded = true;
        },
        errormsg: function(store, message, delay) {
            if (delay === undefined) {delay = 3000;}
            if (typeof message == "string") {message = [message];}
            store.notification = {
                message,
                color: 'error'
            };
            window.setTimeout(function() {
                store.notification = false;
            }, delay);
        },
        successmsg: function(store, message, delay) {
            if (delay === undefined) {delay = 3000;}
            if (typeof message == "string") {message = [message];}
            store.notification = {
                message,
                color: 'success'
            };
            window.setTimeout(function() {
                store.notification = false;
            }, delay);
        },
        setnav: function(state, n) {
            state.navvisible = n;
        },
    },
    getters: {
        appname: function(state) {
            return (state.config.appname != undefined) ? state.config.appname : '';
        },
        loaded: function(state) {
            return state.user != false && state.config != false;
        }
    },
    actions: {
        getinfo() {
            return new Promise((resolve, reject) => {
                axios.get('/api/info').then((ret) => {
                    resolve(ret.data);
                }).catch((error) => {
                    reject(error);
                });
            });
        }
    }
});

import App from './App.vue';

const app = window.app = new Vue({
    el: '#app',
    computed: mapState(['user']),
    router,
    store,
    render: (h) => h(App)
})

