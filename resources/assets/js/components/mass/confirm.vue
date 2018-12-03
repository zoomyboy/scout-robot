<template>
    <v-dialog v-model="open" max-width="600px">
        <v-card v-if="open && type && way">
            <v-card-title>
                <v-btn class="ma-0 mr-2 yellow darken-2" round icon dark><v-icon>fa-bell</v-icon></v-btn>
                Warnung: Daten unvollständig!
            </v-card-title>
            <v-divider></v-divider>
            <v-container v-html="realMessages.join('<hr>')"></v-container>
            <v-divider></v-divider>
            <v-btn @click="$emit('reject'); open=false" color="grey darken-2" class="red darken-3" flat outline>Abbrechen</v-btn>
            <v-btn @click="$emit('resolve'); open=false" class="primary" color="grey darken-2" flat outline>Weiter</v-btn>
        </v-card>
    </v-dialog>
</template>

<script>
	import {mapState} from 'vuex';
    import merge from 'merge';

    export default {
        data: function() {
            return  {
                open: false,
                type: '',
                way: '',
                messages: {
                    email: {
                        emailHeading: 'Du solltest unter der <a href="/config">Konfiguration - E-Mails</a> eine E-Mail-Überschrift angeben.',
                        groupname: 'Du solltest unter der <a href="/config">Konfiguration - Allgemein</a> einen Gruppennamen angeben. Dieser wird im Betreff der E-Mail genutzt.'
                    },
                    post: {},
					pdf: {
						letterIban: 'Du solltest unter der <a href="/config">Konfiguration - PDF-Erstellung</a> deine Kontodaten hinterlegen.',
						letterBic: 'Du solltest unter der <a href="/config">Konfiguration - PDF-Erstellung</a> deine Kontodaten hinterlegen.',
						personName: 'PERSON',
						personTel: 'PERSON',
						personAddress: 'PERSON',
						personMail: 'PERSON',
						personZip: 'PERSON',
						personCity: 'PERSON',
						personFunction: 'Der Ansprechpartner unter <a href="/config">Konfiguration - Ansprechpartner</a> hat keine Funktion',
						website: 'Du solltest unter der <a href="/config">Konfiguration - Allgemein</a> eine Webseite angeben.',
						letterFrom: 'Du solltest unter der <a href="/config">Konfiguration - PDF-Erstellung</a> einen Absender angeben.'
					}
                },
                confirm: 'E-Mail trotzdem senden'
            };
        },
        computed: {
            ...mapState(['config']),
            realMessages: function() {
                var texts = [];
				var forPerson = 'Du solltest unter der <a href="/config">Konfiguration - Ansprechpartner</a> einen Ansprechpartner mit Kontaktdaten angeben.';

				var wayTexts = Object.keys(this.messages[this.way]).forEach((v) => {
                    if (!this.config[v] || !this.config[v].length) {
                        texts.push(this.messages[this.way][v]);
                    }
                });

				var pdfTexts = Object.keys(this.messages.pdf).forEach((v) => {
                    if (!this.config[v] || !this.config[v].length) {
                        texts.push(this.messages.pdf[v]);
                    }
                });

                return texts.map((t) => {
                    if (t == 'PERSON') {return forPerson;}
                    return t;
                });
            }
        },
        methods: {
        },
        mounted: function() {
            var vm = this;

            this.$on('open', function(way, type) {
                vm.type = type;
                vm.way = way;
                if (vm.realMessages.length == 0) {
                    window.setTimeout(function() {
                    vm.$emit('resolve');
                    }, 0);
                } else {
                    vm.open = true;
                }
            });
        }
    }
</script>
