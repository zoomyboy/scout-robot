<template>
    <v-dialog v-model="open" max-width="600px" >
        <v-card>
            <v-card-title>
                <v-btn class="ma-0 mr-2 yellow darken-2" round icon dark>
                    <v-icon v-if="dialog.icon" v-html="dialog.icon"></v-icon>
                    <span v-if="dialog.title" v-html="dialog.title"></span>
                </v-btn>
            </v-card-title>
            <v-divider></v-divider>
            <v-container v-html="dialog.message"></v-container>
            <v-divider></v-divider>
            <v-btn @click="reject()" color="grey darken-2" class="red darken-3" flat outline>Abbrechen</v-btn>
            <v-btn @click="resolve()" class="primary" color="grey darken-2" flat outline>Weiter</v-btn>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        data: function() {
            return {
                open: true
            };
        },
        props: {
            dialog: {}
        },
        created() {
            console.log('AAA');
        },
        methods: {
            reject() {
                this.dialog.reject();
                this.open = false;
            },
            resolve() {
                this.dialog.resolve();
                this.open = false;
            }
        },
        mounted() {
            console.log('mounted');
        }
    }
</script>
