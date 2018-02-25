<template>
    <v-card>
        <v-dialog v-model="adddialog" max-width="400px">
            <v-card>
                <v-card-title>Zahlung Hinzufügen</v-card-title>
                <v-divider></v-divider>
                <v-container>
                    <v-form v-model="addValid" @submit.prevent="triggerAdd">
                        <v-text-field v-model="add.nr" required label="Name" :rules="[validateRequired()]"></v-text-field>
                        <v-select 
                            :items="statuses"
                            v-model="add.status"
                            label="Status"
                            item-text="title"
                            item-value="id"
                            required
                            :rules="[validateSelected()]"
                        >
                        </v-select>
                        <v-select 
                            :items="subscriptions"
                            v-model="add.subscription"
                            label="Beitrag"
                            item-text="title"
                            item-value="id"
                            required
                            :rules="[validateSelected()]"
                        >
                        </v-select>
                        <v-btn :disabled="!addValid" type="submit" class="primary ma-0">Absenden</v-btn>
                    </v-form>
                </v-container>
            </v-card>
        </v-dialog>
        <v-dialog v-model="editdialog" max-width="400px">
            <v-card>
                <v-card-title>Zahlung {{ editData.nr }} bearbeiten</v-card-title>
                <v-divider></v-divider>
                <v-container>
                    <v-form v-model="editValid">
                        <v-text-field v-model="editData.nr" @submit.prevent="triggerEdit" required label="Name" :rules="[validateRequired()]"></v-text-field>
                        <v-select 
                            :items="statuses"
                            v-model="editData.status"
                            label="Status"
                            item-text="title"
                            item-value="id"
                            required
                            :rules="[validateSelected()]"
                        >
                        </v-select>
                        <v-select 
                            :items="subscriptions"
                            v-model="editData.subscription"
                            label="Beitrag"
                            item-text="title"
                            item-value="id"
                            required
                            :rules="[validateSelected()]"
                        >
                        </v-select>
                        <v-btn :disabled="!editValid" type="submit" class="primary ma-0">Absenden</v-btn>
                    </v-form>
                </v-container>
            </v-card>
        </v-dialog>
        <v-dialog v-model="deletedialog" max-width="400px">
            <v-card>
                <v-card-title>Zahlung {{ deleteData.nr }} löschen</v-card-title>
                <v-divider></v-divider>
                <v-container>
                    Möchtest du Zahlung löschen?
                </v-container>
                <v-divider></v-divider>
                <v-container>
                    <v-btn color="primary darken-3" @click="deletedialog = false" dark outline>Abbrechen</v-btn>
                    <v-btn color="red darken-3" @click="triggerDelete()" dark outline>Löschen</v-btn>
                </v-container>
            </v-card>
        </v-dialog>
        <v-toolbar dark color="primary">
            <v-btn @click.native="$emit('close')" icon dark flat><v-icon>close</v-icon></v-btn>
            <v-toolbar-title>Beiträge für Mitglied {{ member.firstname }} {{ member.lastname }} bearbeiten</v-toolbar-title>
            <v-spacer></v-spacer>
        </v-toolbar>
        <v-container>
            <v-toolbar class="blue darken-3" dark>
                <v-toolbar-items>
                    <v-btn @click="adddialog=true; add = {status: 1, nr: '', subscription: member.subscription_id}" flat>Hinzufügen</v-btn>
                    <v-btn @click="billDialog = !billDialog; rememberDialog = false" flat>
                        <v-icon v-if="!billDialog">fa-money</v-icon><v-icon v-if="billDialog">fa-close</v-icon>
                        &nbsp;&nbsp;
                        <span v-if="!billDialog">Rechnung erstellen</span><span v-if="billDialog">Schließen</span>
                    </v-btn>
                    <v-btn @click="rememberDialog=!rememberDialog; billDialog = false" flat>
                        <v-icon v-if="!rememberDialog">fa-money</v-icon><v-icon v-if="rememberDialog">fa-close</v-icon>
                        &nbsp;&nbsp;
                        <span v-if="!rememberDialog">Erinnerung erstellen</span><span v-if="rememberDialog">Schließen</span>
                    </v-btn>
                </v-toolbar-items>
            </v-toolbar>
    		<v-divider></v-divider>
    		<v-container  class="pr-4 pl-4 grey darken-2" v-if="billDialog" grid-list-md fluid dark>
                <v-form @submit.prevent="displayBill">
                    <v-checkbox v-model="includeFamilies" label="Familien einbeziehen" dark></v-checkbox>
                    <v-menu full-width :close-on-content-click="false">
                        <v-text-field slot="activator" ref="deadline" v-model="deadline" label="Deadline" dark></v-text-field>
                        <v-date-picker v-model="deadline" no-title scrollable></v-date-picker>
                    </v-menu>
                    <v-btn type="submit" class="primary" dark>Rechnung öffnen</v-btn>
                </v-form>
            </v-container>
    		<v-container  class="pr-4 pl-4 grey darken-2" v-if="rememberDialog" grid-list-md fluid dark>
                <v-form @submit.prevent="displayRemember">
                    <v-checkbox v-model="includeFamilies" label="Familien einbeziehen" dark></v-checkbox>
                    <v-menu full-width :close-on-content-click="false">
                        <v-text-field slot="activator" ref="deadline" v-model="deadline" label="Deadline" dark></v-text-field>
                        <v-date-picker v-model="deadline" no-title scrollable></v-date-picker>
                    </v-menu>
                    <v-btn type="submit" class="primary" dark>Erinnerung öffnen</v-btn>
                </v-form>
            </v-container>
            <v-divider></v-divider>
            <v-data-table
                v-bind:headers="[
                    {text: 'Nr', value: 'nr', align: 'left'},
                    {text: 'Status', value: 'status.title', align: 'left'},
                    {text: 'Beitrag', value: 'subscription.title', align: 'left'},
                    {text: 'Aktion', sortable: false, value: 'id', align: 'left'},
                ]"
                :items="payments"
                :rows-per-page-items="[10, 25, 50, {text: 'Alle', value: -1}]"
                rows-per-page-text="Einträge pro Seite"
                dark expand must-sort
            >
                <template slot="items" slot-scope="prop">
                    <td>{{ prop.item.nr }}</td>
                    <td>{{ prop.item.status.title }}</td>
                    <td>{{ prop.item.subscription.title }} ({{ money(prop.item.subscription.amount) }})</td>
                    <td>
                        <v-btn-toggle>
                            <v-btn @click="editdialog=true; editModel(prop.item)"><v-icon>fa-pencil</v-icon></v-btn>
                            <v-btn @click="deletedialog=true; deleteModel(prop.item)"><v-icon>fa-trash</v-icon></v-btn>
                        </v-btn-toggle>
                    </td>
                </template>
            </v-data-table>
        </v-container>
    </v-card>
</template>

<style lang="less">
    .cp-wrap.cp-member-payment {

    }
</style>

<script>
    import {mapState} from 'vuex';

    export default {
        data: function() {
            return  {
                payments: [],
                adddialog: false,
                addValid: false,
                add: {
                    nr: '',
                    status: null,
                    subscription: null
                },
                editdialog: false,
                editValid: false,
                editData: {
                    id: -1,
                    nr: '',
                    status: null,
                    subscription: null
                },
                deletedialog: false,
                deleteData: {
                    id: -1,
                    nr: ''
                },
                billDialog: false,
                rememberDialog: false,
                includeFamilies: false,
                deadline: ''
            };
        },
        watch: {
            member: function(m) {
                this.getPayments(m);
            }
        },
        computed: {
            ...mapState(['statuses', 'subscriptions', 'config'])
        },
        props: {
            member: {
                type: Object,
                default: {}
            }
        },
        methods: {
            triggerAdd: function() {
                var vm = this;

                axios.post('/api/member/'+this.member.id+'/payments', this.add).then((ret) => {
                    vm.getPayments(vm.member);
                    vm.adddialog = false;
                });
            },
            triggerEdit: function() {
                var vm = this;

                axios.patch('/api/member/'+this.member.id+'/payments/'+this.editData.id, this.editData).then((ret) => {
                    vm.getPayments(vm.member);
                    vm.editdialog = false;
                });
            },
            triggerDelete: function() {
                var vm = this;

                axios.delete('/api/member/'+this.member.id+'/payments/'+this.deleteData.id).then((ret) => {
                    vm.getPayments(vm.member);
                    vm.deletedialog = false;
                });
            },
            getPayments: function(m) {
                var vm = this;

                axios.get('/api/member/'+m.id+'/payments').then((ret) => {
                    vm.payments = ret.data;
                });
            },
            editModel: function(payment) {
                this.editData = {
                    id: payment.id,
                    nr: payment.nr,
                    status: payment.status_id,
                    subscription: payment.subscription_id
                };
            },
            deleteModel: function(payment) {
                this.deleteData = {nr: payment.nr, id: payment.id};
            },
            dl: require('./c_deadline.js').default,
            displayBill: function() {
                axios.post('/member/'+this.member.id+'/billpdf', {
                    includeFamilies: this.includeFamilies,
                    deadline: this.deadline
                }).then((ret) => {
                    window.open(ret.data);
                });
            },
            displayRemember: function() {
                axios.post('/member/'+this.member.id+'/rememberpdf', {
                    includeFamilies: this.includeFamilies,
                    deadline: this.deadline
                }).then((ret) => {
                    window.open(ret.data);
                });
            }
        },
        mounted: function() {
            var vm = this;

            vm.includeFamilies = this.config.includeFamilies;
            vm.deadline = this.dl();
        }
    }
</script>
