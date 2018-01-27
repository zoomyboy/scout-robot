<template>
    <div class="cp-wrap cp-js-App" v-if="ready">
        <v-app>
            <v-toolbar color="primary" app flat dark>
                <v-toolbar-title class="white--text">{{ appname }} - {{ apptitle }}</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                <v-btn flat v-for="(item, index) in toolbar" :key="index" :href="item.href" target="_BLANK">
                    <v-icon medium v-if="item.icon">{{ item.icon }}</v-icon>
                    {{ item.title ? item.title : '' }}
                </v-btn>
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
            return {};
        },
        methods: {
        },
        computed: {
            ...mapState(['notification', 'appname', 'ready', 'toolbar', 'apptitle']),
        },
        mounted: function() {
            var vm = this;

            axios.get('/api/freeinfo').then(function(ret) {
                vm.$store.commit('setappname', ret.data.app.name);
                vm.$store.commit('ready');
            });
        }
    }
</script>
