<template>
    <v-card color="grey lighten-4">
        <v-form v-model="valid">
            <v-tabs v-model="active" centered>
                <v-tabs-bar class="blue darken-2" dark>
                    <v-tabs-item key="tab-general" href="#tab-general" ripple>Allgemein</v-tabs-item>
                    <v-tabs-item key="tab-contact" href="#tab-contact" ripple>Kontakt</v-tabs-item>
                    <v-tabs-item key="tab-system" href="#tab-system" ripple>System</v-tabs-item>
                    <v-tabs-item key="tab-misc" href="#tab-misc" ripple>Sonstiges</v-tabs-item>
                    <v-tabs-slider color="yellow"></v-tabs-slider>
                </v-tabs-bar>
                <v-tabs-items>

                    <!-- Allgemein -->
                    <v-tabs-content key="tab-general" id="tab-general">
                        <v-container grid-list-md class="pt-4 pl-4 pr-4" fluid>
                            <v-layout row wrap>
                                <v-flex md12>
                                    <v-select
                                        :items="genders"
                                        v-model="values.gender"
                                        label="Geschlecht"
                                        item-text="title"
                                        item-value="id"
                                        clearable
                                    >
                                    </v-select>
                                </v-flex>
                                <v-flex md6>
                                    <v-text-field v-model="values.firstname" label="Vorname" :rules="[validateRequired()]" required></v-text-field>
                                </v-flex>
                                <v-flex md6>
                                    <v-text-field v-model="values.lastname" label="Nachname" :rules="[validateRequired()]" required></v-text-field>
                                </v-flex>
                                <v-flex md6>
                                    <v-text-field v-model="values.address" label="Adresse" :rules="[validateRequired()]" required></v-text-field>
                                </v-flex>
                                <v-flex md6>
                                    <v-text-field v-model="values.further_address" label="Adresszusatz"></v-text-field>
                                </v-flex>
                                <v-flex md6>
                                    <v-text-field v-model="values.zip" label="PLZ" :rules="[validateRequired()]" required></v-text-field>
                                </v-flex>
                                <v-flex md6>
                                    <v-text-field v-model="values.city" label="Stadt" :rules="[validateRequired()]" required></v-text-field>
                                </v-flex>
                                <v-flex md6>
                                    <v-menu full-width :close-on-content-click="false">
                                        <v-text-field slot="activator" ref="birthday" :rules="[validateRequired()]" v-model="values.birthday" label="Geburtsdatum" required></v-text-field>
                                        <v-date-picker v-model="values.birthday" no-title scrollable></v-date-picker>
                                    </v-menu>
                                </v-flex>
                                <v-flex md6>
                                    <v-menu full-width :close-on-content-click="false">
                                        <v-text-field slot="activator" ref="joined_at" :rules="[validateRequired()]" v-model="values.joined_at" label="Eintrittsdatum" required></v-text-field>
                                        <v-date-picker v-model="values.joined_at" no-title scrollable></v-date-picker>
                                    </v-menu>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-tabs-content>

                    <!-- Kontakt -->
                    <v-tabs-content key="tab-contact" id="tab-contact">
                        <v-container grid-list-md class="pt-4 pl-4 pr-4" fluid>
                            <v-layout row wrap>
                                <v-flex md6>
                                    <v-text-field v-model="values.phone" label="Telefonnummer"></v-text-field>
                                </v-flex>
                                <v-flex md6>
                                    <v-text-field v-model="values.mobile" label="Mobilfunknummer"></v-text-field>
                                </v-flex>
                                <v-flex md6>
                                    <v-text-field v-model="values.business_phone" label="Gesch. Nummer"></v-text-field>
                                </v-flex>
                                <v-flex md6>
                                    <v-text-field v-model="values.email" label="E-Mail-Adresse"></v-text-field>
                                </v-flex>
                                <v-flex md6>
                                    <v-text-field v-model="values.email_parents" label="E-Mail-Adresse Erz. Berechtigter"></v-text-field>
                                </v-flex>
                                <v-flex md6>
                                    <v-text-field v-model="values.fax" label="Fax"></v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-tabs-content>

                    <!-- System -->
                    <v-tabs-content key="tab-system" id="tab-system">
                        <v-container grid-list-md class="pt-4 pl-4 pr-4" fluid>
                            <v-layout row wrap>
                                <v-flex md6>
                                    <v-select
                                        :items="ways"
                                        v-model="values.way"
                                        label="Rechnung versenden über"
                                        item-text="title"
                                        item-value="id"
                                        :rules="[validateSelected()]"
                                        required
                                    >
                                    </v-select>
                                    <v-select
                                        :items="subscriptions"
                                        v-model="values.subscription"
                                        label="Beitrag"
                                        item-text="title"
                                        item-value="id"
                                        clearable
                                    >
                                    </v-select>
                                </v-flex>
                                <v-flex md6>
                                    <v-switch v-model="values.keepdata" label="Datenweiterverwendung" hint="Wenn dieses Feld aktiviert wird, wird ein Mitglied beim Löschen zu den abgemeldeten Mitgliedern hinzugefügt, sodass dessen Daten noch eingesehen werden können. Wird das Mitglied mit NaMi synchronisiert, wird der Status dort auf 'inaktiv' gesetzt und auch dort bleiben die Daten bestehen.<br>Ist dieses Feld deaktiviert, werden die Daten komplett gelöscht.<br>Der Standardwert kann allgemein unter der globalen Konfiguration eingestellt werden." persistent-hint></v-switch>
                                    <v-switch v-model="values.sendnewspaper" label="Zeitschriftenversand" hint="Wenn dieses Feld aktiviert wird, bekommt ein Mitglied die Mittendrin-Zeitschrift zugesendet. Der Standardwert kann allgemein unter der globalen Konfiguration eingestellt werden." persistent-hint></v-switch>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-tabs-content>

                    <!-- Sonstiges -->
                    <v-tabs-content key="tab-misc" id="tab-misc">
                        <v-container grid-list-md class="pt-4 pl-4 pr-4" fluid>
                            <v-layout row wrap>
                                <v-flex md6>
                                    <v-text-field v-model="values.nickname" label="Spitzname"></v-text-field>
                                </v-flex>
                                <v-flex md6>
                                    <v-text-field v-model="values.other_country" label="Andere Staatsangehörigeit"></v-text-field>
                                </v-flex>
                                <v-flex md6>
                                    <v-select
                                        :items="regions"
                                        v-model="values.region"
                                        label="Bundesland"
                                        item-text="title"
                                        item-value="id"
                                        clearable
                                    >
                                    </v-select>
                                </v-flex>
                                <v-flex md6>
                                    <v-select
                                        :items="nationalities"
                                        v-model="values.nationality"
                                        label="Staatsangehörigeit"
                                        item-text="title"
                                        item-value="id"
                                        clearable
                                        required
                                        :rules="[validateSelected()]"
                                    >
                                    </v-select>
                                </v-flex>
                                <v-flex md6>
                                    <v-select
                                        :items="countries"
                                        v-model="values.country"
                                        label="Land"
                                        item-text="title"
                                        item-value="id"
                                        clearable
                                        required
                                        :rules="[validateSelected()]"
                                    >
                                    </v-select>
                                </v-flex>
                                <v-flex md6>
                                    <v-select
                                        :items="confessions"
                                        v-model="values.confession"
                                        label="Konfession"
                                        item-text="title"
                                        item-value="id"
                                    >
                                    </v-select>
                                </v-flex>
                                <v-flex md6>
                                    <v-text-field v-model="values.letter_address" label="Brief-Adresse" hint="Ist dieses Feld gefüllt, wird diese Adresse in Briefen verwendet anstatt der gegebenen." persistent-hint multi-line></v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-tabs-content>
                </v-tabs-items>
            </v-tabs>
            <div class="pa-4">
                <v-btn :disabled="!valid" @click="submit" color="primary" class="ma-0">Absenden</v-btn>
            </div>
        </v-form>
    </v-card>
</template>

<script>
    import {mapState} from 'vuex';
    import moment from 'moment';

    export default {
        data: function() {
            return {
                valid: true,
                active: 'tab-general',
                values: {}
            }
        },
        computed: {
            ...mapState(['config', 'genders', 'nationalities', 'countries', 'regions', 'ways', 'confessions', 'activities', 'subscriptions'])
        },
        methods: {
            submit: function() {
                var vm = this;

                axios.patch('/api/member/'+this.$route.params.id, this.values).then((ret) => {
                    vm.$store.commit('successmsg', 'Mitglied bearbeitet');
                    this.$router.push({name: 'member.index'});
                }).catch((error) => vm.showErrors(error));
            }
        },
        mounted: function() {
            var vm = this;

            axios.get('/api/member/'+this.$route.params.id).then((ret) => {
                vm.member = ret.data;
                vm.$store.commit('settitle', 'Mitglied '+ret.data.firstname+' '+ret.data.lastname+' bearbeiten');

                vm.$set(vm.values, 'firstname', ret.data.firstname);
                vm.$set(vm.values, 'lastname', ret.data.lastname);
                vm.$set(vm.values, 'address', ret.data.address);
                vm.$set(vm.values, 'zip', ret.data.zip);
                vm.$set(vm.values, 'city', ret.data.city);
                vm.$set(vm.values, 'further_address', ret.data.further_address);
                vm.$set(vm.values, 'birthday', moment(ret.data.birthday).format('YYYY-MM-DD'));
                vm.$set(vm.values, 'joined_at', moment(ret.data.joined_at).format('YYYY-MM-DD'));
                vm.$set(vm.values, 'gender', ret.data.gender_id);

                vm.$set(vm.values, 'phone', ret.data.phone);
                vm.$set(vm.values, 'mobile', ret.data.mobile);
                vm.$set(vm.values, 'fax', ret.data.fax);
                vm.$set(vm.values, 'email', ret.data.email);
                vm.$set(vm.values, 'business_phone', ret.data.business_phone);
                vm.$set(vm.values, 'email_parents', ret.data.email_parents);
                vm.$set(vm.values, 'way', ret.data.way_id);
                vm.$set(vm.values, 'subscription', ret.data.subscription_id);
                vm.$set(vm.values, 'keepdata', ret.data.keepdata);
                vm.$set(vm.values, 'sendnewspaper', ret.data.sendnewspaper);
                vm.$set(vm.values, 'letter_address', ret.data.letter_address);

                vm.$set(vm.values, 'nickname', ret.data.nickname);
                vm.$set(vm.values, 'other_country', ret.data.other_country);
                vm.$set(vm.values, 'region', ret.data.region_id);
                vm.$set(vm.values, 'nationality', ret.data.nationality_id);
                vm.$set(vm.values, 'confession', ret.data.confession_id);
                vm.$set(vm.values, 'country', ret.data.country_id);
            });

        }
    }
</script>
