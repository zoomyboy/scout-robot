<template>
    <div>
        <v-toolbar class="blue darken-3" dark>
            <v-toolbar-items>
                <v-btn @click="adding = true" flat>Hinzufügen</v-btn>
            </v-toolbar-items>
        </v-toolbar>
        <v-divider></v-divider>
        <v-dialog v-model="adding" max-width="600px">
            <v-card>
                <v-card-title>Hinzufügen</v-card-title>
                <v-divider></v-divider>
                <v-container>
                    <v-form v-model="addValid">
                        <v-text-field v-model="add.title" required label="Name" :rules="[validateRequired()]"></v-text-field>
                        <v-text-field v-model="add.amount" required label="Beitrag" :rules="[validateRequired(), validateCurrency()]"></v-text-field>
                        <v-select 
                            :items="fees"
                            v-model="add.fee"
                            label="NaMi-Beitrag"
                            item-text="title"
                            item-value="id"
                        >
                        </v-select>
                        <v-btn :disabled="!addValid" @click="triggerAdd" class="primary ma-0">Absenden</v-btn>
                    </v-form>
                </v-container>
            </v-card>
        </v-dialog>
        <v-dialog v-model="editing" max-width="600px">
            <v-card>
                <v-card-title>Bearbeiten von Beitrag {{ edit.title }}</v-card-title>
                <v-divider></v-divider>
                <v-container>
                    <v-form v-model="editValid">
                        <v-text-field v-model="edit.title" required label="Name" :rules="[validateRequired()]"></v-text-field>
                        <v-text-field v-model="edit.amount" required label="Beitrag" :rules="[validateRequired(), validateCurrency()]"></v-text-field>
                        <v-select 
                            :items="fees"
                            v-model="edit.fee"
                            label="NaMi-Beitrag"
                            item-text="title"
                            item-value="id"
                        >
                        </v-select>
                        <v-btn :disabled="!editValid" @click="triggerEdit" class="primary ma-0">Absenden</v-btn>
                    </v-form>
                </v-container>
            </v-card>
        </v-dialog>
        <v-data-table v-bind:headers="[
            {text: 'Name', value: 'title', align: 'left'},
            {text: 'Beitrag', value: 'amount', align: 'left'},
            {text: 'NaMi-Beitrag', value: 'fee.title', align: 'left'}
            ]"
            :items="subscriptions"
            v-if="subscriptions"
            dark expand must-sort
        >
            <template slot="items" slot-scope="prop">
                <td>{{ prop.item.title }}</td>
                <td>{{ money(prop.item.amount) }}</td>
                <td>{{ prop.item.fee.title }}</td>
                <td>
                    <v-btn-toggle>
                        <v-btn @click="showEdit(prop.item)"><v-icon>fa-pencil</v-icon></v-btn>
                    </v-btn-toggle>
                </td>
            </template>
        </v-data-table>
	</div>
</template>

<style lang="less">
	.cp-wrap.cp-fee-index {

	}
</style>

<script>
    import accounting from 'accounting';
    import {mapState} from 'vuex';

	export default {
		data: function() {
			return  {
                adding: false,
                addValid: false,
                editValid: false,
                add: {
                    fee: null,
                    amount: '',
                    title: ''
                },
                edit: {},
                editing: false
			};
		},
        computed: {
            ...mapState(['fees', 'subscriptions'])
        },
		components: {
		},
        methods: {
            money: function(m) {
                return accounting.formatMoney(m / 100, '€', 2, ",", ",", '%v %s');
            },
            triggerAdd: function() {
                var vm = this;

                var data = {
                    amount: accounting.unformat(this.add.amount),
                    title: this.add.title,
                    fee: this.add.fee
                };

                axios.post('/api/subscription', data).then(function(ret) {
                    vm.$store.commit('setsubscriptions', ret.data);
                    vm.adding = false;
                }).catch((err) => this.showErrors(err));
            },
            triggerEdit: function() {
                var vm = this;

                var data = {
                    id: this.edit.id,
                    amount: accounting.unformat(this.edit.amount),
                    title: this.edit.title,
                    fee: this.edit.fee
                };

                axios.patch('/api/subscription/'+this.edit.id, data).then(function(ret) {
                    vm.$store.commit('setsubscriptions', ret.data);
                    vm.editing = false;
                }).catch((err) => this.showErrors(err));
            },
            showEdit: function(item) {
                this.editing = true;
                this.edit = {
                    id: item.id,
                    amount: accounting.formatMoney(item.amount / 100, '', 2, ",", ","),
                    title: item.title,
                    fee: item.fee_id
                };
            }
        },
		mounted: function() {
			var vm = this;
		}
	}
</script>
