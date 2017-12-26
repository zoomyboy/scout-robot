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
						<vf-form action="/pdf/bill" method="post" :ajax="false" target="_blank">
							<vf-checkbox name="includeFamilies" :value="config.includeFamilies" label="Familien zusammenführen"></vf-checkbox>
						
							<vf-checkbox name="wayPost" label="Post-Wege einbeziehen" :value="true"></vf-checkbox>
							<vf-checkbox name="wayEmail" label="E-Mail-Wege einbeziehen"></vf-checkbox>
						
							<vf-date name="deadline" :value="deadline" label="Deadline"></vf-date>
						
							<vf-submit>Alle Rechnungen erstellen</vf-submit>
						</vf-form>
					</div>

					<div class="col-md-6">
						<legend>Massen-Erinnerung</legend>
						<vf-form action="/pdf/remember" method="post" :ajax="false" target="_blank">
							<vf-checkbox name="includeFamilies" :value="config.includeFamilies" label="Familien zusammenführen"></vf-checkbox>
						
							<vf-checkbox name="wayPost" label="Post-Wege einbeziehen" :value="true"></vf-checkbox>
							<vf-checkbox name="wayEmail" label="E-Mail-Wege einbeziehen" :value="true"></vf-checkbox>
						
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
						<vf-form action="/pdf/bill" method="post" :ajax="false" target="_blank">
							<vf-checkbox name="includeFamilies" :value="config.includeFamilies" label="Familien zusammenführen"></vf-checkbox>
						
							<vf-checkbox name="wayPost" label="Post-Wege einbeziehen" :value="true"></vf-checkbox>
							<vf-checkbox name="wayEmail" label="E-Mail-Wege einbeziehen"></vf-checkbox>
							<vf-checkbox name="updatePayments" label="Betroffene Zahlungen nach dem Senden in 'Rechnung ausgestellt' umwandeln"></vf-checkbox>
						
							<vf-date name="deadline" :value="deadline" label="Deadline"></vf-date>
						
							<vf-submit>E-Mail versenden</vf-submit>
						</vf-form>
					</div>

					<div class="col-md-6">
						<legend>Massen-Erinnerung</legend>
						<vf-form action="/pdf/remember" method="post" :ajax="false" target="_blank">
							<vf-checkbox name="includeFamilies" :value="config.includeFamilies" label="Familien zusammenführen"></vf-checkbox>
						
							<vf-checkbox name="wayPost" label="Post-Wege einbeziehen" :value="true"></vf-checkbox>
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
		mounted: function() {

		}
	}
</script>
