<template>
    <v-card color="grey lighten-4">
        <v-form v-model="valid" @submit.prevent="submit">
            <v-container grid-list-md class="pt-4 pl-4 pr-4" fluid>
                <v-checkbox label="Aktive Mitglieder" v-model="values.active"></v-checkbox>
                <v-checkbox label="Inaktive Mitglieder" v-model="values.inactive"></v-checkbox>
            </v-container>
            <div class="pa-4">
                <v-btn :disabled="!valid" type="submit" color="primary" class="ma-0">Synchronisation starten</v-btn>
            </div>
        </v-form>
    </v-card>
</template>

<script>
	export default {
		data: function() {
			return  {
                valid: true,
                values: {
                    active: true,
                    inactive: false
                }
			};
		},
		components:  {
		},
		methods: {
			submit: function() {
                var vm = this;

                axios.post('/api/nami/getmembers', this.values).then(function() {
                    vm.$store.commit('successmsg', 'Import wurde beauftragt. Das kann einige Zeit dauern …');
                }).catch((error) => vm.showErrors(error));
			}
		},
		created: function() {
			this.$store.commit('settitle', 'NaMi-Abruf');
		},
		mounted: function() {

		}
	}
</script>
