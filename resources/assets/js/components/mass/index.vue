<template>
	<div class="cp-wrap cp-mass-index">
		<panel>
			<div slot="tabs" class="tabs">
				<tab title="Per Post" index="1" active></tab>
				<tab title="Per Email" index="2"></tab>
			</div>
			<panelcontent index="1" active>
				<div class="row">
					<div class="col-md-6">
						<legend>Massen-Rechnung</legend>
						<vf-form action="/pdf/bill" method="post" @afterpersist="displaypdf" ref="postBillForm">
							<vf-checkbox name="includeFamilies" :value="config.includeFamilies" label="Familien zusammenführen"></vf-checkbox>
						
							<vf-checkbox name="wayPost" label="Post-Wege einbeziehen" :value="true"></vf-checkbox>
							<vf-checkbox name="wayEmail" label="E-Mail-Wege einbeziehen"></vf-checkbox>
						
							<vf-date name="deadline" :value="deadline" label="Deadline"></vf-date>
						
							<v-link @click="submitPostBill">Alle Rechnungen anzeigen</v-link>
						</vf-form>
					</div>

					<div class="col-md-6">
						<legend>Massen-Erinnerung</legend>
						<vf-form action="/pdf/remember" method="post" @afterpersist="displaypdf" ref="postRememberForm">
							<vf-checkbox name="includeFamilies" :value="config.includeFamilies" label="Familien zusammenführen"></vf-checkbox>
						
							<vf-checkbox name="wayPost" label="Post-Wege einbeziehen" :value="true"></vf-checkbox>
							<vf-checkbox name="wayEmail" label="E-Mail-Wege einbeziehen"></vf-checkbox>
						
							<vf-date name="deadline" :value="deadline" label="Deadline"></vf-date>
						
							<v-link @click="submitPostRemember">Alle Erinnerungen anzeigen</v-link>
						</vf-form>
					</div>
				</div>
			</panelcontent>
			<panelcontent index="2">
				<div class="row">
					<div class="col-md-6">
						<legend>Massen-Rechnung</legend>
						<vf-form action="/api/mass/email/bill" method="post" target="_blank" msg="Senden erfolgreich" ref="emailBillForm">
							<vf-checkbox name="includeFamilies" :value="config.includeFamilies" label="Familien zusammenführen"></vf-checkbox>
						
							<vf-checkbox name="wayPost" label="Post-Wege einbeziehen"></vf-checkbox>
							<vf-checkbox name="wayEmail" label="E-Mail-Wege einbeziehen" :value="true"></vf-checkbox>
							<vf-checkbox name="updatePayments" label="Betroffene Zahlungen nach dem Senden in 'Rechnung ausgestellt' umwandeln"></vf-checkbox>
						
							<vf-date name="deadline" :value="deadline" label="Deadline"></vf-date>
						
							<v-link @click="submitEmailBill">Rechnung versenden</v-link>
						</vf-form>
					</div>

					<div class="col-md-6">
						<legend>Massen-Erinnerung</legend>
						<vf-form action="/api/mass/email/remember" method="post" target="_blank" msg="Senden erfolgreich" ref="emailRememberForm">
							<vf-checkbox name="includeFamilies" :value="config.includeFamilies" label="Familien zusammenführen"></vf-checkbox>
						
							<vf-checkbox name="wayPost" label="Post-Wege einbeziehen"></vf-checkbox>
							<vf-checkbox name="wayEmail" label="E-Mail-Wege einbeziehen" :value="true"></vf-checkbox>
						
							<vf-date name="deadline" :value="deadline" label="Deadline"></vf-date>
						
							<v-link @click="submitEmailRemember">Zahlungserinnerung versenden</v-link>
						</vf-form>
					</div>
				</div>
			</panelcontent>
		</panel>
	</div>
</template>

<style lang="less">
	.cp-wrap.cp-mass-index {

	}
</style>

<script>
	import {mapState} from 'vuex';
	import swal from 'sweetalert2';

	export default {
		data: function() {
			return  {
			
			};
		},
		computed: {
			deadline: require('../member/c_deadline.js').default,
			...mapState({
				config: state => state.config
			}),
			validations: function() {
				var forPerson = 'Du solltest unter der <a href="/config">Konfiguration - Ansprechpartner</a> einen Ansprechpartner mit Kontaktdaten angeben.';

				return {
					email: {
						emailHeading: 'Du solltest unter der <a href="/config">Konfiguration - E-Mails</a> eine E-Mail-Überschrift angeben.',
						groupname: 'Du solltest unter der <a href="/config">Konfiguration - Allgemein</a> einen Gruppennamen angeben. Dieser wird im Betreff der E-Mail genutzt.'
					},
					pdf: {
						letterIban: 'Du solltest unter der <a href="/config">Konfiguration - PDF-Erstellung</a> deine Kontodaten hinterlegen.',
						letterBic: 'Du solltest unter der <a href="/config">Konfiguration - PDF-Erstellung</a> deine Kontodaten hinterlegen.',
						personName: forPerson,
						personTel: forPerson,
						personAddress: forPerson,
						personMail: forPerson,
						personZip: forPerson,
						personCity: forPerson,
						personFunction: 'Der Ansprechpartner unter <a href="/config">Konfiguration - Ansprechpartner</a> hat keine Funktion',
						website: 'Du solltest unter der <a href="/config">Konfiguration - Allgemein</a> eine Webseite angeben.',
						letterFrom: 'Du solltest unter der <a href="/config">Konfiguration - PDF-Erstellung</a> einen Absender angeben.'
					}
				};
			}
		},
		components: {
			panel: function(resolve) {
				require(['z-ui/panel/panel.vue'], resolve);
			},
			vfDate: require('z-ui/form/fields/date.vue'),
			tab: require('z-ui/panel/tab.vue'),
			panelcontent: require('z-ui/panel/content.vue')
		},
		methods: {
			displaypdf: function(data, ret) {
				window.open(ret);
			},
			validate(validations) {
				var texts = [];

				validations.forEach((validationCollection) => {
					Object.keys(validationCollection).forEach((v) => {
						if (!this.config[v] || !this.config[v].length) {
							texts.push(validationCollection[v]);
						}
					});
				});

				return texts;
			},
			swal: function(title, html, confirm, type, confirmed, aborted) {
				swal({
					title: title,
					type: type,
					html: html,
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					confirmButtonText: confirm,
					cancelButtonText: 'Doch nicht',
					confirmButtonClass: 'btn btn-success no-right-border-radius',
					cancelButtonClass: 'btn btn-danger no-left-border-radius',
					buttonsStyling: false
				}).then(function(result) {
					if (result != undefined && result.value == true) {
						confirmed();
					} else {
						aborted();
					}
				});
			},
			confirm: function(confirmed) {
				var vm = this;

				this.swal('E-Mail senden', 'Bitte bestätige das Senden dieser E-Mails. Alle Mitglieder kriegen E-Mails, die Zahlungen mit Status "nicht bezahlt" haben.', 'E-Mail senden', 'warning', function() {
					confirmed();
				}, function() {});
			},
			triggerSubmit: function(validations, confirmText, confirmed) {
				var vm = this;
				var check = true;

				var texts = this.validate(validations);

				if (texts.length) {
					this.swal('Warnung: Daten unvollständig!', texts.join('<hr>'), confirmText, 'warning', function() {
						confirmed();
					}, function() {});

					return;
				}

				confirmed();
			},
			submitEmailBill: function() {
				var vm = this;

				this.triggerSubmit([this.validations.email, this.validations.pdf], 'E-Mail trotzdem senden', function() {
					vm.confirm(function() {
						vm.$refs.emailBillForm.submit();
					});
				});
			},
			submitEmailRemember() {
				var vm = this;

				this.triggerSubmit([this.validations.email, this.validations.pdf], 'E-Mail trotzdem senden', function() {
					vm.confirm(function() {
						vm.$refs.emailRememberForm.submit();
					});
				});
			},
			submitPostBill: function() {
				var vm = this;

				this.triggerSubmit([this.validations.pdf], 'Trotzdem anzeigen', function() {
					vm.$refs.postBillForm.submit()
				});
			},
			submitPostRemember() {
				var vm = this;

				this.triggerSubmit([this.validations.pdf], 'Trotzdem anzeigen', function() {
					vm.$refs.postRememberForm.submit()
				});
			}
		},
		mounted: function() {

		}
	}
</script>
