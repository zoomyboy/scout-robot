<template>
    <v-card color="grey lighten-4">
        <v-card-text>
            <v-form v-model="valid">
                <v-container fluid>
                    <v-layout row>
                        <v-flex xs4>
                            <v-subheader>Passwort</v-subheader>
                        </v-flex>
                        <v-flex xs8>
                            <v-text-field
                                name="password" label="Passwort" hint="Mindestens 6 Zeichen eingeben"
                                v-model="values.password" min="6" type="password" :rules="[validateMin(6)]" counter validate-on-blur></v-text-field>
                        </v-flex>
                    </v-layout>
                <v-layout row>
                    <v-flex xs4>
                        <v-subheader>Passwort widerholen</v-subheader>
                    </v-flex>
                    <v-flex xs8>
                        <v-text-field
                            name="password_confirmation" label="Passwort widerholen" hint="Mindestens 6 Zeichen eingeben"
                            v-model="values.password_confirmation" min="6" type="password" :rules="[validateMin(6)]" counter validate-on-blur></v-text-field>
                    </v-flex>
                </v-layout>

                <v-btn :disabled="!valid" @click="submit" color="primary">Absenden</v-btn>
                </v-container>
            </v-form>
        </v-card-text>
    </v-card>
</template>

		
<script>
	import {mapState} from 'vuex';

	export default {
        data: function() {
            return {
                valid: false,
                values: {
                    password: '',
                    password_confirmation: ''
                }
            };
        },
        computed: mapState(['user']),
		methods: {
            submit: function() {
                var vm = this;

                axios.patch('/api/profile/'+this.user.id+'/password', this.values).then((ret) => {
                    vm.$store.commit('successmsg', 'Dein Passwort wurde erfolgreich geändert.');
                }).catch((error) => {
                    var field = Object.keys(error.response.data)[0];
                    var msg = error.response.data[Object.keys(error.response.data)[0]];
                    vm.$store.commit('errormsg', 'Fehler in Feld '+field+': '+msg);
                });
            }
		}
	};
</script>
