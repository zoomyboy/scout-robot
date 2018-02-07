<template>
    <v-card>
        <v-dialog v-model="adddialog" max-width="400px">
            <v-card>
                <v-card-title>Zahlung Hinzufügen</v-card-title>
                <v-divider></v-divider>
                <v-container>
                    <v-form v-model="addValid">
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
                        <v-btn :disabled="!addValid" @click="triggerAdd" class="primary ma-0">Absenden</v-btn>
                    </v-form>
                </v-container>
            </v-card>
        </v-dialog>
        <v-dialog v-model="editdialog" max-width="400px">
            <v-card>
                <v-card-title>Zahlung {{ edit.nr }} bearbeiten</v-card-title>
                <v-divider></v-divider>
                <v-container>
                    <v-form v-model="editValid">
                        <v-text-field v-model="edit.nr" required label="Name" :rules="[validateRequired()]"></v-text-field>
                        <v-select 
                            :items="statuses"
                            v-model="edit.status"
                            label="Status"
                            item-text="title"
                            item-value="id"
                            required
                            :rules="[validateSelected()]"
                        >
                        </v-select>
                        <v-select 
                            :items="subscriptions"
                            v-model="edit.subscription"
                            label="Beitrag"
                            item-text="title"
                            item-value="id"
                            required
                            :rules="[validateSelected()]"
                        >
                        </v-select>
                        <v-btn :disabled="!editValid" @click="triggerEdit" class="primary ma-0">Absenden</v-btn>
                    </v-form>
                </v-container>
            </v-card>
        </v-dialog>
        <v-toolbar dark color="primary">
            <v-btn @click.native="$emit('close')" icon dark flat><v-icon>close</v-icon></v-btn>
            <v-btn @click="adddialog=true" icon dark><v-icon>fa-plus</v-icon></v-btn>
            <v-toolbar-title>Beiträge für Mitglied {{ member.firstname }} {{ member.lastname }} bearbeiten</v-toolbar-title>
            <v-spacer></v-spacer>
        </v-toolbar>
        <v-container>
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
                            <v-btn><v-icon>fa-close</v-icon></v-btn>
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
                edit: {
                    id: -1,
                    nr: '',
                    status: null,
                    subscription: null
                },
            };
        },
        watch: {
            member: function(m) {
                this.getPayments(m);
            }
        },
        computed: {
            ...mapState(['statuses', 'subscriptions'])
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

                axios.patch('/api/member/'+this.member.id+'/payments/'+this.edit.id, this.edit).then((ret) => {
                    vm.getPayments(vm.member);
                    vm.editdialog = false;
                });
            },
            getPayments: function(m) {
                var vm = this;

                axios.get('/api/member/'+m.id+'/payments').then((ret) => {
                    vm.payments = ret.data;
                });
            },
            editModel: function(payment) {
                this.edit = {
                    id: payment.id,
                    nr: payment.nr,
                    status: payment.status_id,
                    subscription: payment.subscription_id
                };
            }
        },
        mounted: function() {
            var vm = this;
        }
    }
</script>
