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
						<vf-form action="/pdf/bill" method="post" @afterpersist="displaypdf">
							<vf-checkbox name="includeFamilies" :value="config.includeFamilies" label="Familien zusammenführen"></vf-checkbox>
						
							<vf-checkbox name="wayPost" label="Post-Wege einbeziehen" :value="true"></vf-checkbox>
							<vf-checkbox name="wayEmail" label="E-Mail-Wege einbeziehen"></vf-checkbox>
						
							<vf-date name="deadline" :value="deadline" label="Deadline"></vf-date>
						
							<vf-submit>Alle Rechnungen erstellen</vf-submit>
						</vf-form>
					</div>

					<div class="col-md-6">
						<legend>Massen-Erinnerung</legend>
						<vf-form action="/pdf/remember" method="post" @afterpersist="displaypdf">
							<vf-checkbox name="includeFamilies" :value="config.includeFamilies" label="Familien zusammenführen"></vf-checkbox>
						
							<vf-checkbox name="wayPost" label="Post-Wege einbeziehen" :value="true"></vf-checkbox>
							<vf-checkbox name="wayEmail" label="E-Mail-Wege einbeziehen"></vf-checkbox>
						
							<vf-date name="deadline" :value="deadline" label="Deadline"></vf-date>
						
							<vf-submit>Alle Erinnerungen erstellen</vf-submit>
						</vf-form>
					</div>
				</div>
			</panelcontent>
			<panelcontent index="2">
				<div class="row">
					<div class="col-md-6">
						<legend>Massen-Rechnung</legend>
						<vf-form action="/api/mass/email/bill" method="post" target="_blank" @beforepersist="alert('I');" ref="emailBillForm">
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
						<vf-form action="/api/mass/email/remember" method="post" target="_blank">
							<vf-checkbox name="includeFamilies" :value="config.includeFamilies" label="Familien zusammenführen"></vf-checkbox>
						
							<vf-checkbox name="wayPost" label="Post-Wege einbeziehen"></vf-checkbox>
							<vf-checkbox name="wayEmail" label="E-Mail-Wege einbeziehen" :value="true"></vf-checkbox>
						
							<vf-date name="deadline" :value="deadline" label="Deadline"></vf-date>
						
							<vf-submit>E-Mail versenden</vf-submit>
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
			submitEmailBill: function() {
				var vm = this;
				var check = true;
				var texts = [];

				if (!this.config.emailHeading || !this.config.emailHeading.length) {
					check = false;
					texts.push('Du solltest unter der <a href="/config">Konfiguration - E-Mails</a> eine E-Mail-Überschrift angeben.');
				}

				if (!this.config.groupname || !this.config.groupname.length) {
					check = false;
					texts.push('Du solltest unter der <a href="/config">Konfiguration - Allgemein</a> einen Gruppennamen angeben. Dieser wird im Betreff der E-Mail genutzt.');
				}

				if (!this.config.letterIban || !this.config.letterIban.length
				  || !this.config.letterBic || !this.config.letterBic.length
				) {
					check = false;
					texts.push('Du solltest unter der <a href="/config">Konfiguration - PDF-Erstellung</a> deine Kontodaten hinterlegen.');
				}

				if (
				  !this.config.personName || !this.config.personName.length
				  || !this.config.personTel || !this.config.personTel.length
				  || !this.config.personMail || !this.config.personMail.length
				  || !this.config.personAddress || !this.config.personAddress.length
				  || !this.config.personZip || !this.config.personZip.length
				  || !this.config.personCity || !this.config.personCity.length
				) {
					check = false;
					texts.push('Du solltest unter der <a href="/config">Konfiguration - Ansprechpartner</a> einen Ansprechpartner mit Kontaktdaten angeben.');
				}

				if ( !this.config.personFunction || !this.config.personFunction.length) {
					check = false;
					texts.push('Der Ansprechpartner unter <a href="/config">Konfiguration - Ansprechpartner</a> hat keine Funktion');
				}

				if ( !this.config.website || !this.config.website.length) {
					check = false;
					texts.push('Du solltest unter der <a href="/config">Konfiguration - Allgemein</a> eine Webseite angeben.');
				}

				if ( !this.config.letterFrom || !this.config.letterFrom.length) {
					check = false;
					texts.push('Du solltest unter der <a href="/config">Konfiguration - PDF-Erstellung</a> einen Absender angeben.');
				}

				if (check) {
					vm.$refs.emailBillForm.submit();

					return;
				}

				swal({
					title: 'Warnung: Daten unvollständig!',
					type: 'warning',
					html: texts.join('<hr>'),
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					confirmButtonText: 'E-Mail trotzdem senden',
					confirmButtonClass: 'btn btn-success no-right-border-radius',
					cancelButtonClass: 'btn btn-danger no-left-border-radius',
					buttonsStyling: false
				}).then(function(result) {
					if (result != undefined && result.value == true) {
						vm.$refs.emailBillForm.submit();
					}
				});
			}
		},
		mounted: function() {

		}
	}
</script>
