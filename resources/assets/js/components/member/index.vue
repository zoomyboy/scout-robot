<template>
    <div class="v-comp">
        <v-dialog fullscreen v-model="paymentsModal">
            <payments @close="paymentsModal = false" :member="payments"></payments>
        </v-dialog>
        <v-toolbar class="blue darken-3" dark>
            <v-toolbar-items>
                <v-btn @click="$router.push({name: 'member.add'})" class="" flat>Hinzufügen</v-btn>
                <v-btn @click="filterVisible=!filterVisible" flat>
                    <v-icon v-if="!filterVisible">fa-filter</v-icon><v-icon v-if="filterVisible">fa-close</v-icon>
                    &nbsp;&nbsp;
                    <span v-if="!filterVisible">Filter</span><span v-if="filterVisible">Schließen</span>
                </v-btn>
                <v-text-field label="Name suchen" v-model="filter.name" class="ml-3"></v-text-field>
            </v-toolbar-items>
        </v-toolbar>
        <v-divider></v-divider>
        <v-container grid-list-md fluid class="pr-4 pl-4 grey darken-2" dark v-if="filterVisible">
            <v-layout row wrap dark>
                <v-flex md6 lg4>
                    <v-text-field dark v-model="filter.name" label="Name"></v-text-field>
                </v-flex>
                <v-flex md6 lg4>
                    <v-text-field dark v-model="filter.location" label="Adresse/PLZ/Ort"></v-text-field>
                </v-flex>
                <v-flex xs12>
                    <v-subheader>Status</v-subheader>
                    <v-checkbox v-model="filter.active" label="Aktiv"></v-checkbox>
                    <v-checkbox v-model="filter.inactive" label="Inaktiv"></v-checkbox>
                </v-flex>
            </v-layout>
        </v-container>
        <v-divider v-if="filter"></v-divider>

        <v-data-table
            v-bind:headers="[
                {text: 'Nachname', value: 'lastname', align: 'left'},
                {text: 'Vorname', value: 'firstname', align: 'left'},
                {text: 'Adresse', value: 'address', align: 'left'},
                {text: 'PLZ', value: 'zip', align: 'left'},
                {text: 'Ort', value: 'city', align: 'left'},
                {text: 'Ausstände', value: 'strikes', align: 'left'},
                {text: 'Aktion', sortable: false, value: 'id', align: 'left'},
            ]"
            :items="filteredMembers"
            :rows-per-page-items="[10, 25, 50, {text: 'Alle', value: -1}]"
            rows-per-page-text="Einträge pro Seite"
            dark expand must-sort
        >
            <template slot="items" slot-scope="{item}">
                <td>{{ item.lastname }}</td>
                <td>{{ item.firstname }}</td>
                <td>{{ item.address }}</td>
                <td>{{ item.zip }}</td>
                <td>{{ item.city }}</td>
                <td>{{ item.present.strikes }}</td>
                <td>
                    <v-btn-toggle>
                        <v-btn @click="$router.push({name: 'member.edit', params: {id: item.id}})"><v-icon>fa-pencil</v-icon></v-btn>
                        <v-btn @click="paymentsModal = true; payments = item"><v-icon>fa-money</v-icon></v-btn>
                        <v-btn @click="cancelMember(item)"><v-icon>fa-close</v-icon></v-btn>
                    </v-btn-toggle>
                </td>
            </template>
        </v-data-table>
    </div>
</template>

<script>
    import { mapActions, mapState, mapGetters } from 'vuex';
    import Payments from './Payments';

    export default {
        data: function() {
            return {
                member: false,
                payments: {},
                paymentsModal: false,
                filter: {
                    location: '',
                    name: '',
                    active: true,
                    inactive: false
                },
                filterVisible: false
            };
        },
        watch: {
            filterVisible: function(n) {
                if (n === false) {
                    this.filter = {
                        location: '',
                        name: ''
                    };
                }
            }
        },
        components: { Payments },
        computed: {
            filteredMembers() {
                return this.members.filter((m) => {
                    var hasLocation = true;
                    var hasName = true;
                    var activeState = true;
                    var inactiveState = true;

                    if (this.filter.name == '' && this.filter.location == '') {
                        hasName = true;
                        hasLocation = true;
                    }
                    if (this.filter.name != '') {
                        hasName = (m.firstname + m.lastname).toLowerCase().indexOf(this.filter.name.toLowerCase()) !== -1;
                    }
                    if (this.filter.location != '') {
                        hasLocation = (m.address + m.zip + m.city).toLowerCase().indexOf(this.filter.location.toLowerCase()) !== -1;
                    }

                    if (this.filter.active == false && m.active) {return false;}
                    if (this.filter.inactive == false && !m.active) {return false;}

                    return hasLocation && hasName;
                });
            },
            members() {
                return this.$store.getters['member/mappedMembers'];
            }
        },
        methods: {
            cancelMember(member) {
                this.confirm('Wollen Sie dieses Mitglied abmelden')
                    .then(() => this.$store.commit('member/cancel', member))
                ;
            },
            ...mapActions(['confirm'])
        },
        created: function() {
            this.$store.commit('member/getmembers');
            this.$store.commit('settitle', 'Mitglieder-Übersicht');
        }
    }
</script>

