<template>
    <v-card color="grey lighten-4">
        <v-card-text>
            <v-form v-model="valid">
                <v-container fluid>
                    <v-layout row>
                        <v-flex xs4>
                            <v-subheader>Name</v-subheader>
                        </v-flex>
                        <v-flex xs8>
                            <v-text-field name="name" label="Name" id="name" required validate-on-blur :rules="[validateLength()]" v-model="values.name"></v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-layout row>
                        <v-flex xs4>
                            <v-subheader>E-Mail-Adresse</v-subheader>
                        </v-flex>
                        <v-flex xs8>
                            <v-text-field name="email" label="E-Mail-Adresse" id="email" :rules="[
                                validateLength(),
                                validateEmail()
                            ]" v-model="values.email" required validate-on-blur></v-text-field>
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
                valid: false
            };
        },
        computed: {
            values: function() {
                return {
                    name: this.user.name,
                    email: this.user.email
                };
            },
            ...mapState(['user'])
        },
		methods: {
			updateStore(u) {
				this.$store.commit('updateuser', u);
			},
            submit: function() {
                var vm = this;

                axios.patch('/api/profile/'+this.user.id, this.values).then((ret) => {
                    vm.$store.commit('successmsg', 'Update erfolgreich!');
                }).catch((error) => {
                    var field = Object.keys(error.response.data)[0];
                    var msg = error.response.data[Object.keys(error.response.data)[0]];
                    vm.$store.commit('errormsg', 'Fehler in Feld '+field+': '+msg);
                });
            }
		},
		created: function() {
			this.$store.commit('settitle', 'Mein Profil');
		}
	};
</script>
