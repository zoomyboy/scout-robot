<template>
    <div>
        <confirm-dialog @resolve="confirm.resolve" @reject="confirm.reject" ref="confirm"></confirm-dialog>
        <confirm-dialog-after @resolve="confirm.resolve" @reject="confirm.reject" ref="confirmAfter"></confirm-dialog-after>
        <v-card color="grey lighten-4">
            <v-tabs v-model="activeTab" centered>
                <v-tabs-bar class="blue darken-2" dark>
                    <v-tabs-item key="email" href="#email" ripple>Per E-Mail</v-tabs-item>
                    <v-tabs-item key="post" href="#post" ripple>Per Post</v-tabs-item>
                    <v-tabs-slider color="yellow"></v-tabs-slider>
                </v-tabs-bar>
                <v-tabs-items>
                    <!-- E-Mail -->
                    <v-tabs-content key="email" id="email">
                        <v-container grid-list-md class="pt-4 pl-4 pr-4">
                            <v-layout row wrap>
                                <v-flex md12>
                                    <v-checkbox label="Familien zusammenführen" v-model="email.includeFamilies"></v-checkbox>
                                    <v-checkbox
                                        label="Zahlungsstatus aktualisieren"
                                        v-model="email.updatePayments"
                                        hint="Dies setzt jede Zahlung, die in der Rechnug steht automatisch auf 'Rechnung ausgestellt'"
                                        persistent-hint
                                    >
                                    </v-checkbox>
                                    <v-checkbox label="Post-Wege einbeziehen" v-model="email.wayPost"></v-checkbox>
                                    <v-checkbox label="E-Mail-Wege einbeziehen" v-model="email.wayEmail"></v-checkbox>
                                    <v-menu full-width :close-on-content-click="false">
                                        <v-text-field slot="activator" ref="deadline" v-model="email.deadline" label="Deadline"></v-text-field>
                                        <v-date-picker v-model="email.deadline" no-title scrollable></v-date-picker>
                                    </v-menu>
                                    <v-btn type="submit" @click="confirmGeneral('email', 'bill', 'emailBill')" class="primary ml-0">Rechnungen senden</v-btn>
                                    <v-btn type="submit" @click="confirmGeneral('email', 'remember', 'emailRemember')" class="primary">Erinnerungen senden</v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-tabs-content>

                    <!-- Post -->
                    <v-tabs-content key="post" id="post">
                        <v-container grid-list-md class="pt-4 pl-4 pr-4">
                            <v-layout row wrap>
                                <v-flex md12>
                                    <v-checkbox label="Familien zusammenführen" v-model="email.includeFamilies"></v-checkbox>
                                    <v-checkbox label="Post-Wege einbeziehen" v-model="post.wayPost"></v-checkbox>
                                    <v-checkbox label="E-Mail-Wege einbeziehen" v-model="post.wayEmail"></v-checkbox>
                                    <v-menu full-width :close-on-content-click="false">
                                        <v-text-field slot="activator" ref="deadline" v-model="post.deadline" label="Deadline"></v-text-field>
                                        <v-date-picker v-model="post.deadline" no-title scrollable></v-date-picker>
                                    </v-menu>
                                    <v-btn type="submit" @click="confirmGeneral('post', 'bill', 'postBill')" class="primary ml-0">Rechnungen senden</v-btn>
                                    <v-btn type="submit" @click="confirmGeneral('post', 'remember', 'postRemember')" class="primary">Erinnerungen senden</v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-tabs-content>
                </v-tabs-items>
            </v-tabs>
        </v-card>
    </div>
</template>

<script>
	import {mapState} from 'vuex';

	export default {
		data: function() {
			return  {
			    activeTab: 'email',
                confirm: {
                    resolve: function() {},
                    reject: function() {}
                },
                email: {
                    includeFamilies: false,
                    wayPost: false,
                    wayEmail: true,
                    updatePayments: false
                },
                post: {
                    includeFamilies: false,
                    wayPost: false,
                    wayEmail: true
                }
			};
		},
		computed: {
			...mapState(['config']),
			deadline: require('../member/c_deadline.js').default,
		},
        components: {
            confirmDialog: require('./confirm.vue'),
            confirmDialogAfter: require('./confirm-after.vue')
        },
		methods: {
			displaypdf: function(data, ret) {
				window.open(ret);
			},
			confirmGeneral: function(way, type, callback) {
				var vm = this;

                new Promise((resolve, reject) => {
                    vm.confirm = {resolve, reject};
                    vm.$refs.confirm.$emit('open', way, type);
                }).then((ret) => {
                    return new Promise((resolve, reject) => {
                        vm.confirm = {resolve, reject};
                        vm.$refs.confirmAfter.$emit('open', way, type);
                    });
                }).then((ret) => {
                    eval('vm.'+callback+'()');
                }).catch((err) => {});
			},
            emailBill: function() {
                axios.post('/api/mass/email/bill', this.email);
            },
            emailRemember: function() {
                axios.post('/api/mass/email/remember', this.email);
            },
            postBill: function() {
                axios.post('/pdf/bill', this.post).then((ret) => {
                    window.open(ret.data);
                });
            },
            postRemember: function() {
                axios.post('/pdf/remember', this.post).then((ret) => {
                    window.open(ret.data);
                });
            },
		},
		mounted: function() {
            this.email.includeFamilies = this.config.includeFamilies;
            this.email.deadline = this.deadline;
            this.post.includeFamilies = this.config.includeFamilies;
            this.post.deadline = this.deadline;
		}
	}
</script>
