<template>
    <v-card color="grey lighten-4">
        <v-form v-model="valid">
            <v-subheader>Herzlich Willkommen bei Scout Robot. Bitte gebe deine Logindaten ein.</v-subheader>
            <v-container fluid>
                <v-layout row>
                    <v-flex xs4>
                        <v-subheader>E-Mail-Adresse</v-subheader>
                    </v-flex>
                    <v-flex xs8>
                        <v-text-field name="email" label="E-Mail-Adresse" id="email" required :rules="[validateEmail()]" v-model="values.email"></v-text-field>
                    </v-flex>
                </v-layout>
                <v-layout row>
                    <v-flex xs4>
                        <v-subheader>Passwort</v-subheader>
                    </v-flex>
                    <v-flex xs8>
                        <v-text-field name="password" label="Passwort" id="password" required :rules="[validateLength()]" v-model="values.password" type="password"></v-text-field>
                    </v-flex>
                </v-layout>
            </v-container>
            <v-btn :disabled="!valid" @click="submit" color="primary">Absenden</v-btn>
        </v-form>
    </v-card>
	<!-- <div>
		<br><br>
		<vf-form action="/login" method="post" follow>
			<vf-text name="email" label="E-Mail-Adresse" help="Gebe hier die E-Mail-Adresse deines Zugangs ein."></vf-text>
			<vf-password name="password" label="Passwort"  help="Gebe hier das Passwort deines Zugangs ein."></vf-password>
			<vf-submit>Einloggen</vf-submit>
			<v-link route="newpw">Passwort vergessen</v-link>
		</vf-form>
    </div> -->
</template>

<script>
    export default {
        data: function() {
            return {
                valid: false,
                values: {
                    email: '',
                    password: ''
                }
            };
        },
        methods: {
            submit: function() {
                var vm = this;
    
                axios.post('/login', this.values).then((ret) => {
                    window.locatin.href = '/';
                }).catch((error) => {
                    var field = Object.keys(error.response.data)[0];
                    var msg = error.response.data[Object.keys(error.response.data)[0]];
                    vm.$store.commit('errormsg', 'Fehler in Feld '+field+': '+msg, 5000);
                });
            }
        },
        created: function() {
            this.$store.commit('settitle', 'Einloggen');
        },
    };
</script>
