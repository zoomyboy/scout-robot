<template>
    <div class="cp-wrap cp-createpayments-index">
        <v-card color="grey lighten-4">
            <v-form v-model="valid" @submit.prevent="submit">
                <v-container grid-list-md class="pt-4 pl-4 pr-4" fluid>
                    <v-text-field name="nr" v-model="values.nr" label="Nummer der Zahlung" hint="In der Regel die Jahreszahl" persistent-hint :rules="[validateRequired()]"></v-text-field>
                </v-container>
                <div class="pa-4">
                    <v-btn :disabled="!valid" type="submit" color="primary" class="ma-0">Zahlungen erstellen</v-btn>
                </div>
            </v-form>
        </v-card>
    </div>
</template>

<style lang="less">
    .cp-wrap.cp-createpayments-index {

    }
</style>

<script>
    export default {
        data: function() {
            return {
                valid: false,
                values: {
                    nr: ''
                }
            };
        },
        created: function() {
            this.$store.commit('settitle', 'Zahlungen erstellen');
        },
        methods: {
            submit: function() {
                var vm = this;

                axios.post('/api/paymentbatch', this.values).then((ret) => {
                    vm.$store.commit('successmsg', 'Zahlungen wurden erstellt!');
                });
            }
        },
        mounted: function() {

        }
    }
</script>
