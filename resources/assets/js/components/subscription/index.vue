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
                        <v-btn @click=""><v-icon>fa-pencil</v-icon></v-btn>
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
				subscriptions: [],
                adding: false,
                addValid: false,
                add: {
                    fee: null,
                    amount: '',
                    title: ''
                }
			};
		},
        computed: {
            ...mapState(['fees'])
        },
		components: {
		},
        methods: {
            money: function(m) {
                return accounting.formatMoney(m / 100, '€', 2, ",", ",", '%v %s');
            },
            triggerAdd: function() {
                var vm = this;

                axios.post('/api/subscription', this.add).then(function(ret) {
                    vm.$store.commit('setsubscriptions', ret.data);
                    vm.subscriptions = ret.data;
                    vm.adding = false;
                }).catch((err) => showErrors(err));
            }
        },
		mounted: function() {
			var vm = this;

			axios.get('/api/subscription').then(function(data) {
				vm.subscriptions = data.data;
			});
		}
	}
</script>
