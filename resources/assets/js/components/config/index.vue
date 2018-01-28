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
                        <v-container grid-list-md class="pt-4 pl-4 pr-4">
                            <v-text-field name="groupname" label="Gruppenname" id="groupname" validate-on-blur v-model="values.groupname"></v-text-field>
                            <v-text-field name="website" label="Website" id="website" validate-on-blur v-model="values.website"></v-text-field>
                        </v-container>
                    </v-tabs-content>

                    <!-- Mitgliederverwaltung -->
                    <v-tabs-content key="tab-member" id="tab-member">
                        <v-container grid-list-md class="pt-4 pl-4 pr-4">
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
                            <v-checkbox label="Datenweiterverwendung" name="default_keepdata" v-model="default_keepdata" id="default_keepdata"></v-checkbox>
                            <v-checkbox label="Zeitschriftenversand" name="default_sendnewspaper" v-model="default_sendnewspaper" id="default_sendnewspaper"></v-checkbox>
                        </v-container>
                    </v-tabs-content>

                    <!-- Ansprechpartner -->
                    <v-tabs-content key="tab-person" id="tab-person">
                        <v-container grid-list-md class="pt-4 pl-4 pr-4">
                            <v-layout row wrap>
                                <v-flex xs12 sm6><v-text-field name="personName" label="Name" id="personName" validate-on-blur v-model="values.personName"></v-text-field></v-flex>
                                <v-flex xs12 sm6><v-text-field name="personMail" label="E-Mail-Adresse" id="personMail" validate-on-blur v-model="values.personMail"></v-text-field></v-flex>
                                <v-flex xs12 sm6><v-text-field name="personTel" label="Telefonnummer" id="personTel" validate-on-blur v-model="values.personTel"></v-text-field></v-flex>
                                <v-flex xs12 sm6><v-text-field name="personFunction" label="Funktion" id="personFunction" validate-on-blur v-model="values.personFunction"></v-text-field></v-flex>
                                <v-flex xs12 sm6><v-text-field name="personAddress" label="Adresse" id="personAddress" validate-on-blur v-model="values.personAddress"></v-text-field></v-flex>
                                <v-flex xs12 sm6><v-text-field name="personCity" label="Stadt" id="personCity" validate-on-blur v-model="values.personCity"></v-text-field></v-flex>
                            </v-layout>
                        </v-container>
                    </v-tabs-content>

                    <!-- PDF-Erstellung -->
                    <v-tabs-content key="tab-pdf" id="tab-pdf">
                        <v-container grid-list-md class="pt-4 pl-4 pr-4">
                            <v-layout row wrap>
                                <v-flex xs12 sm6>
                                    <v-text-field name="letterKontoName" label="Konto Empfänger" id="letterKontoName" validate-on-blur v-model="values.letterKontoName"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <v-text-field name="letterIban" label="IBAN" id="letterIban" validate-on-blur v-model="values.letterIban"></v-text-field>
                                </v-flex>
								<v-flex xs12 sm6>
									<v-text-field name="letterBic" label="BIC" id="letterBic" validate-on-blur v-model="values.letterBic"></v-text-field>
                                </v-flex>
								<v-flex xs12 sm6>
									<v-text-field name="letterFrom" label="Absender in Rechnungen" id="letterFrom" validate-on-blur v-model="values.letterFrom"></v-text-field>
                                </v-flex>
								<v-flex xs12 sm6>
                                    <v-text-field name="letterZweck" label="Verwendungszweck" id="letterZweck" validate-on-blur v-model="values.letterZweck"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <v-text-field name="letterDate" label="Datumsangabe" id="letterDate" validate-on-blur v-model="values.letterDate"></v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-layout row>
                                <v-checkbox label="Familien standardmäßig zusammenführen" name="includeFamilies" v-model="includeFamilies" id="includeFamilies"></v-checkbox>
                            </v-layout>
							<v-layout row wrap>
								<v-flex xs12><v-subheader class="pr-0 pl-0">Standard-Deadline</v-subheader></v-flex>
								<v-flex xs12 sm6>
									<v-text-field name="deadlinenr" label="Anzahl" id="deadlinenr" validate-on-blur v-model="values.deadlinenr"></v-text-field>
								</v-flex>
								<v-flex xs12 sm6>
									<v-select
										:items="timeunits"
										v-model="values.deadlineunit"
										label="Einheit"
										item-text="title"
										item-value="id"
										hint="Einheit für die Deadline"
										clearable
									>
									</v-select>
								</v-flex>
							</v-layout>
                        </v-container>
                    </v-tabs-content>

                    <!-- E-Mails -->
                    <v-tabs-content key="tab-email" id="tab-email">
                        <v-container grid-list-md class="pt-4 pl-4 pr-4">
							<v-text-field name="emailHeading" label="Überschrift" id="emailHeading" validate-on-blur v-model="values.emailHeading"></v-text-field>
							<v-text-field name="emailBillText" label="Text für Rechnungen" id="emailBillText" validate-on-blur multi-line v-model="values.emailBillText"></v-text-field>
							<v-text-field name="emailRememberText" label="Text für Zahlungserinnerungen" id="emailRememberText" multi-line validate-on-blur v-model="values.emailRememberText"></v-text-field>
							<v-text-field name="emailGreeting" label="Grußwort" id="emailGreeting" validate-on-blur v-model="values.emailGreeting"></v-text-field>
						</v-container>
					</v-tabs-content>

                    <!-- NaMi -->
                    <v-tabs-content key="tab-nami" id="tab-nami">
                        <v-container grid-list-md class="pt-4 pl-4 pr-4">
                            <v-checkbox label="NaMi Synchronisieren" name="namiEnabled" v-model="namiEnabled" id="namiEnabled"></v-checkbox>
							<v-flex wrap row>
								<v-text-field name="namiUser" label="Mitgliedsnummer" id="namiUser" validate-on-blur v-model="values.namiUser"></v-text-field>
								<v-text-field type="password" name="namiPassword" label="Passwort" id="namiPassword" validate-on-blur v-model="values.namiPassword"></v-text-field>
								<v-text-field name="namiGroup" label="Mitgliedsnummer" id="namiGroup" validate-on-blur v-model="values.namiGroup"></v-text-field>
							</v-flex>
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
            default_keepdata: true,
            includeFamilies: true,
            namiEnabled: true
        };
    },
    computed: {
        values: function() {
            return {
                groupname: this.config.groupname,
                website: this.config.website,
                default_keepdata: this.default_keepdata,
                default_sendnewspaper: this.default_sendnewspaper,
				defaultCountry: this.config.default_country ? this.config.default_country : null,
				defaultRegion: this.config.default_region ? this.config.default_region : null,
				defaultNationality: this.config.default_nationality ? this.config.default_nationality : null,
                personName: this.config.personName,
                personMail: this.config.personMail,
                personTel: this.config.personTel,
                personFunction: this.config.personFunction,
                personAddress: this.config.personAddress,
                personCity: this.config.personCity,
                letterKontoName: this.config.letterKontoName,
                letterIban: this.config.letterIban,
                letterBic: this.config.letterBic,
                letterDate: this.config.letterDate,
                letterZweck: this.config.letterZweck,
                letterFrom: this.config.letterFrom,
                includeFamilies: this.includeFamilies,
                deadlinenr: this.config.deadlinenr,
                deadlineunit: this.config.deadlineunit ? this.config.deadlineunit.id : null,
                emailHeading: this.config.emailHeading,
                emailRememberText: this.config.emailRememberText,
                emailBillText: this.config.emailBillText,
                emailGreeting: this.config.emailGreeting,
                namiEnabled: this.namiEnabled,
                namiUser: this.config.namiUser,
                namiPassword: this.config.namiPassword,
                namiGroup: this.config.namiGroup,
				files: []
            };
        },
        ...mapState(['config', 'nationalities', 'countries', 'regions', 'timeunits'])
    },
    methods: {
        submit: function() {
            var vm = this;

            axios.patch('/api/conf/1', this.values).then((ret) => {
                vm.$store.commit('successmsg', 'Konfiguration gespeichert');
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
        this.default_keepdata = this.config.default_keepdata;
		this.default_sendnewspaper = this.config.default_sendnewspaper;
		this.includeFamilies = this.config.includeFamilies;
		this.namiEnabled = this.config.namiEnabled;
    }
};
</script>
