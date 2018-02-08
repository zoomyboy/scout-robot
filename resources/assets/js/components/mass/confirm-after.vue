<template>
    <v-dialog v-model="open" max-width="600px">
        <v-card v-if="open && type">
            <v-card-title>
                <v-btn class="ma-0 mr-2 yellow darken-2" round icon dark><v-icon>fa-bell</v-icon></v-btn>
                Bitte bestätige das Senden dieser E-Mails.
            </v-card-title>
            <v-divider></v-divider>
            <v-container>
                Alle Mitglieder kriegen E-Mails, die Zahlungen mit Status "nicht bezahlt" haben.
            </v-container>
            <v-divider></v-divider>
            <v-btn @click="$emit('reject'); open=false" color="grey darken-2" class="red darken-3" flat outline>Abbrechen</v-btn>
            <v-btn @click="$emit('resolve'); open=false" class="primary" color="grey darken-2" flat outline>E-Mails Senden</v-btn>
        </v-card>
    </v-dialog>
</template>

<style lang="less">
    .cp-wrap.cp-mass-confirm {

    }
</style>

<script>
	import {mapState} from 'vuex';

    export default {
        data: function() {
            return  {
                open: false,
                type: ''
            };
        },
        computed: {
            ...mapState(['config'])
        },
        mounted: function() {
            var vm = this;

            this.$on('open', function(type) {
                vm.open = true;
                vm.type = type;
            });
        }
    }
</script>
