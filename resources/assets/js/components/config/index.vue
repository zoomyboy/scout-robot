<template>
    <v-card color="grey lighten-4">
        <v-form v-model="valid">
            <v-tabs v-model="active" centered>
                <v-tabs-bar class="blue darken-2" dark>
                    <v-tabs-item key="tab-general" href="#tab-general" ripple>Allgemein</v-tabs-item>
                    <v-tabs-item key="tab-member" href="#tab-member" ripple>Mitgliederverwaltung</v-tabs-item>
                    <v-tabs-item key="tab-person" href="#tab-person" ripple>Ansprechpartner</v-tabs-item>
                    <v-tabs-item key="tab-pdf" href="#tab-pdf" ripple>PDF-Erstellung</v-tabs-item>
                    <v-tabs-item key="tab-email" href="#tab-email" ripple>E-Mails</v-tabs-item>
                    <v-tabs-item key="tab-nami" href="#tab-nami" ripple>NaMi</v-tabs-item>
                    <v-tabs-slider color="yellow"></v-tabs-slider>
                </v-tabs-bar>
                <v-tabs-items>

                    <!-- Allgemein -->
                    <v-tabs-content key="tab-general" id="tab-general">
                        <v-card-text>
                            <v-layout row>
                                <v-flex xs4>
                                    <v-subheader>Gruppenname</v-subheader>
                                </v-flex>
                                <v-flex xs8>
                                    <v-text-field name="groupname" label="Gruppenname" id="groupname" validate-on-blur v-model="values.groupname"></v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-layout row>
                                <v-flex xs4>
                                    <v-subheader>Website</v-subheader>
                                </v-flex>
                                <v-flex xs8>
                                    <v-text-field name="website" label="Webseite" id="website" validate-on-blur v-model="values.website"></v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-card-text>
                    </v-tabs-content>

                    <!-- Mitgliederverwaltung -->
                    <v-tabs-content key="tab-member" id="tab-member">
                        <v-card-text>
                            <v-layout row>
                                <v-flex xs4>
                                    <v-subheader>Land</v-subheader>
                                </v-flex>
                                <v-flex xs8>
                                    <v-select
                                        :items="countries"
                                        v-model="values.defaultCountry"
                                        label="Land"
                                        item-text="title"
                                        item-value="id"
                                        hint="Standard-Land beim Mitglieder anlegen"
                                        clearable
                                    >
                                    
                                    </v-select>
                                </v-flex>
                            </v-layout>
                            <v-layout row>
                                <v-flex xs4>
                                    <v-subheader>Region</v-subheader>
                                </v-flex>
                                <v-flex xs8>
                                    <v-select
                                        :items="regions"
                                        v-model="values.defaultRegion"
                                        label="Bundesland"
                                        item-text="title"
                                        item-value="id"
                                        hint="Standard-Bundesland beim Mitglieder anlegen"
                                        clearable
                                    >
                                    
                                    </v-select>
                                </v-flex>
                            </v-layout>
                            <v-layout row>
                                <v-flex xs4>
                                    <v-subheader>Staatsangehörigkeit</v-subheader>
                                </v-flex>
                                <v-flex xs8>
                                    <v-select
                                        :items="nationalities"
                                        v-model="values.defaultNationality"
                                        label="Staatsangehörigkeit"
                                        item-text="title"
                                        item-value="id"
                                        hint="Standard-Staatsangehörigkeit beim Mitglieder anlegen"
                                        clearable
                                    >
                                    
                                    </v-select>
                                </v-flex>
                            </v-layout>
                            <v-layout row>
                                <v-flex xs4>
                                    <v-subheader>Datenweiterverwendung</v-subheader>
                                </v-flex>
                                <v-flex xs8>
                                    <v-checkbox required name="default_keepdata" v-model="values.default_keepdata" id="default_keepdata"></v-checkbox>
                                </v-flex>
                            </v-layout>
                            <v-layout row>
                                <v-flex xs4>
                                    <v-subheader>Zeitschriftenversand</v-subheader>
                                </v-flex>
                                <v-flex xs8>
                                    <v-checkbox required name="default_sendnewspaper" v-model="values.default_sendnewspaper" id="default_sendnewspaper"></v-checkbox>
                                </v-flex>
                            </v-layout>
                        </v-card-text>
                    </v-tabs-content>
                    <v-tabs-content key="tab-person" id="tab-person">
                        <v-card-text>
                            <v-layout row>
                                <v-flex xs4>
                                    <v-subheader>Website</v-subheader>
                                </v-flex>
                                <v-flex xs8>
                                    <v-text-field name="website" label="Webseite" id="website" validate-on-blur v-model="values.website"></v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-card-text>
                    </v-tabs-content>
                </v-tabs-items>
            </v-tabs>
        <v-btn :disabled="!valid" @click="submit" color="primary">Absenden</v-btn>  
        </v-form>
    </v-card>
    <!-- <div class="cp-wrap cp-config-index">
        <vf-form :getmodel="config" method="patch" action="/api/conf/1" msg="Konfiguration gespeichert" @afterpersist="updateconfig">
            <grid>
                <article>
                    <panel>
                        <div slot="tabs" class="tabs">
                            <paneltab title="Allgemein" index="0" active></paneltab>
                            <paneltab title="Mitgliederverwaltung" index="1"></paneltab>
                            <paneltab title="Ansprechpartner" index="2"></paneltab>
                            <paneltab title="PDF-Erstellung" index="3"></paneltab>
                            <paneltab title="E-Mails" index="4"></paneltab>
                            <paneltab title="NaMi" index="5"></paneltab>
                        </div>
                        <panelcontent index="0" active>
                            <vf-text name="groupname" label="Gruppenname"></vf-text>
                            <vf-text name="website" label="Webseite"></vf-text>
                        </panelcontent>
                        <panelcontent index="1">
                            <vf-select name="defaultCountry" label="Land" url="/api/country" nullable></vf-select>
                            <vf-select name="defaultRegion" label="Bundesland" url="/api/region" nullable></vf-select>
                            <vf-select name="defaultNationality" label="Staatsangehörigkeit" url="/api/nationality" nullable></vf-select>
                            <vf-checkbox name="default_keepdata" label="Datenweiterverwendung" help="Standardeinstellung für Datenweiterverwendung beim anlegen neuer Mitglieder"></vf-checkbox>
                            <vf-checkbox name="default_sendnewspaper" label="Zeitschriftenversand" help="Standardeinstellung für Zeitschriftenversand beim anlegen neuer Mitglieder"></vf-checkbox>
                        </panelcontent>
                        <panelcontent index="2">
                            <vf-text name="personName" label="Ansprechpartner Name"></vf-text>
                            <vf-text name="personMail" label="Ansprechpartner E-Mail-Adresse"></vf-text>
                            <vf-text name="personTel" label="Ansprechpartner Tel"></vf-text>
                            <vf-text name="personFunction" label="Ansprechpartner Funktion"></vf-text>
                            <vf-text name="personAddress" label="Ansprechpartner Adresse"></vf-text>
                            <vf-text name="personZip" label="Ansprechpartner PLZ"></vf-text>
                            <vf-text name="personCity" label="Ansprechpartner Ort"></vf-text>
                        </panelcontent>
                        <panelcontent index="3">
                            <vf-files name="files" label="Logo im Header" uploadurl="/api/file"></vf-files>
                            <vf-text name="letterKontoName" value="Kontoinhaber"></vf-text>
                            <vf-text name="letterIban" label="IBAN"></vf-text>
                            <vf-text name="letterBic" label="BIC"></vf-text>
                            <vf-text name="letterFrom" label="Absender in Rechnungen"></vf-text>
                            <vf-text name="letterZweck" label="Verwendungszweck" :info="'Verwende [name] als Platzhalter für Mitglieds-Name'"></vf-text>
                            <vf-text name="letterDate" label="Datumsangabe" :info="'Verwende {{ $date }} als Platzhalter für aktuelles Datum'"></vf-text>
                            <vf-checkbox name="includeFamilies" label="Familien standardmäßig zusammenführen" :info="'Familienmitglieder, die in einem Haushalt leben, bekommen standardmäßig nur eine Rechnung'"></vf-checkbox>
                            <label>Standard-Deadline</label>
                            <div class="row">
                                <div class="col-md-6"><vf-text name="deadlinenr"></vf-text></div>
                                <div class="col-md-6"><vf-select name="deadlineunit" url="/api/unit/date"></vf-select></div>
                            </div>
                        </panelcontent>
                        <panelcontent index="4">
                            <vf-text name="emailHeading" label="Überschrift"></vf-text>
                            <vf-text name="emailBillText" label="Text für Rechnungen" :help="billRememberHelp"></vf-text>
                            <vf-text name="emailRememberText" label="Text für Zahlungserinnerungen" :help="billBodyHelp"></vf-text>
                            <vf-text name="emailGreeting" label="Grußwort" :help="greetingHelp"></vf-text>
                        </panelcontent>
                        <panelcontent index="5">
                            <vf-checkbox name="namiEnabled" label="NaMi Synchronisieren"></vf-checkbox>
                            <vf-text name="namiUser" label="Benutzername" help="Das ist in der Regel deine Mitgliedsnummer"></vf-text>
                            <vf-password name="namiPassword" label="Passwort" help="Das hier ist dein NaMi-Passwort. Wenn du noch kein hast, solltest du einen Zugang beantragen."></vf-password>
                            <vf-text name="namiGroup" label="Gruppieerungsnummer" help="Das ist in der Regel deine Stammesnummer (i.d.R. 6-stellig)"></vf-text>
                        </panelcontent>
                        <vf-submit></vf-submit>
                    </panel>
                </article>
            </grid>
        </vf-form>
    </div> -->
</template>

<style>
    .cp-config-index form {
        height: 100%;
    }
</style>

<script>
import {mapState} from 'vuex';

export default {
    data: function() {
        return {
            valid: false,
            active: 'tab-general',
            default_sendnewspaper: true,
            default_keepdata: true
        };
    },
    computed: {
        values: function() {
            return {
                groupname: this.config.groupname,
                website: this.config.website,
                default_keepdata: this.config.default_keepdata,
                default_sendnewspaper: this.config.default_sendnewspaper
            };
        },
        ...mapState(['config', 'nationalities', 'countries', 'regions'])
    },
    methods: {
        submit: function() {
            var vm = this;

            axios.patch('url', this.values).then((ret) => {
                vm.$store.commit('successmsg', 'success');
            }).catch((error) => {
                var field = Object.keys(error.response.data)[0];
                var msg = error.response.data[Object.keys(error.response.data)[0]];
                vm.$store.commit('errormsg', 'Fehler in Feld '+field+': '+msg);
            });
        }
    },
    created: function() {
        this.$store.commit('settitle', 'Konfiguration');
    },
    mounted: function() {
        
    }
};
</script>
