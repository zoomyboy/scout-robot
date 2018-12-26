<template>
    <div class="cp-wrap cp-js-App" v-if="loaded">
        <v-app v-if="loaded">
            <v-navigation-drawer :mobile-break-point="800" v-model="navInternalState" app fixed>
                <v-toolbar flat>
                    <v-list>
                        <v-list-tile>
                            <v-list-tile-title class="title">
                                {{ appname }}
                            </v-list-tile-title>
                        </v-list-tile>
                    </v-list>
                </v-toolbar>
                <v-divider></v-divider>
                <v-list dense :key="index" v-for="(item, index) in navbar">
                    <v-list-tile @click="visitMenu(item)">
                        <v-list-tile-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
                <v-bottom-nav :value="true" color="transparent">
                    <v-form method="post" action="/logout">
                        <v-btn color="primary darken-3" flat type="submit">
                            <span>Abmelden</span><v-icon>fa-sign-out</v-icon>
                        </v-btn>
                    </v-form>
                </v-bottom-nav>
            </v-navigation-drawer>
            <v-toolbar color="primary" app flat dark>
                <v-toolbar-side-icon @click="navInternalState = !navInternalState" app></v-toolbar-side-icon>
                <v-toolbar-title class="white--text">{{ apptitle }}</v-toolbar-title>
                <v-progress-circular v-if="firstRunning != null" :size="40" :width="6" :rotate="-90" :value="firstRunning.amount" color="orange" class="ml-3">
                    {{ firstRunning.amount }}
                </v-progress-circular>
                <v-toolbar-title v-if="firstRunning != null" class="white--text body-2">{{ firstRunning.message }}</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-menu v-for="(item,key) in toolbar" offset-y left :key="key">
                        <v-btn slot="activator" flat>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-btn>
                        <v-list>
                            <v-list-tile :key="subkey" @click="visitMenu(subitem)" v-for="(subitem,subkey) in item.children">
                                <v-list-tile-title>{{ subitem.title }}</v-list-tile-title>
                            </v-list-tile>
                        </v-list>
                    </v-menu>
                </v-toolbar-items>
            </v-toolbar>
            <v-content>
                <v-container fluid>
                    <v-alert :color="notification.color" v-if="notification" icon="check_circle" value="notification !== false">
                        <div v-for="msg in notification.message">
                            {{ msg }}
                        </div>
                    </v-alert>
                    <confirm></confirm>
                    <router-view></router-view>
                </v-container>
            </v-content>
            <v-footer app></v-footer>
        </v-app>
    </div>
</template>

<script>
    import {mapState, mapGetters} from 'vuex';
    import Confirm from './components/Confirm.vue';

    export default {
        data: function() {
            return  {
                navInternalState: false
            };
        },
        components: { Confirm },
        methods: {
            visitMenu: function(entry) {
                if (undefined != entry.href) {
                    window.location.href = entry.href;
                    return;
                }
                if (undefined != entry.route) {
                    this.$router.push({name: entry.route});
                    return;
                }
            }
        },
        watch: {
            navInternalState: function(n) {
                this.$store.commit('setnav', n);
            }
        },
        computed: {
            ...mapState(['toolbar', 'notification', 'apptitle', 'navvisible', 'navbar']),
            ...mapGetters(['appname', 'loaded', 'firstRunning'])
        },
        mounted: function() {
            var vm = this;

            this.listenOnChannels();
        },
        created() {
            this.$store.dispatch('getinfo').then((data) => {
                this.$store.commit('setinfo', data);
            });
        }
    }
</script>
