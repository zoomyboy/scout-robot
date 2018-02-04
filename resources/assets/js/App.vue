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
                <v-list dense :class="'pt-'+index" :key="index" v-for="(item, index) in navbar">
					<v-list-tile @click="visitMenu(item)">
                        <v-list-tile-action>
							<v-icon>{{ item.icon }}</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
							<v-list-tile-title>{{ item.title }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
            </v-navigation-drawer>
            <v-toolbar color="primary" app flat dark>
                <v-toolbar-side-icon @click="navInternalState = !navInternalState" app></v-toolbar-side-icon>
				<v-toolbar-title class="white--text">{{ apptitle }}</v-toolbar-title>
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
						{{ notification.message }}
					</v-alert>
                    <router-view></router-view>
                </v-container>
            </v-content>
            <v-footer app></v-footer>
        </v-app>
    </div>
</template>

<style lang="less">
    .cp-wrap.cp-js-App {

    }
</style>

<script>
</script>

<script>
    import {mapState, mapGetters} from 'vuex';

    export default {
        data: function() {
            return  {
				navInternalState: false
            };
        },
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
            ...mapGetters(['appname', 'loaded'])
        },
        mounted: function() {
            var vm = this;
        },
        created() {
            this.$store.dispatch('getinfo').then((data) => {
                this.$store.commit('setinfo', data);
            });
        }
    }
</script>
