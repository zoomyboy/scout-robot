<template>
	<v-container>
    <v-card color="grey lighten-4">
        <v-form v-model="valid">
            <v-subheader>Herzlich Willkommen bei Scout Robot. Bitte gebe deine Logindaten ein.</v-subheader>
            <v-container fluid>
                <v-text-field name="email" label="E-Mail-Adresse" id="email" required :rules="[validateEmail()]" v-model="values.email"></v-text-field>
                <v-text-field name="password" label="Passwort" id="password" required :rules="[validateLength()]" v-model="values.password" type="password"></v-text-field>
            </v-container>
            <v-btn :disabled="!valid" @click="submit" color="primary">Absenden</v-btn>
        </v-form>
    </v-card>
	</v-container>
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
