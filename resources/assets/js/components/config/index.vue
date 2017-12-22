<template>
	<div class="cp-wrap cp-config-index">
		<vf-form :getmodel="config" method="patch" action="/api/conf/1" msg="Konfiguration gespeichert" @afterpersist="updateconfig">
			<grid>
				<article>
					<panel>
						<div slot="tabs" class="tabs">
							<paneltab title="Mitgliederverwaltung" index="0" active></paneltab>
							<paneltab title="PDF-Erstellung" index="1"></paneltab>
						</div>
						<panelcontent index="0" active>
							<vf-select name="defaultCountry" label="Land" url="/api/country" nullable></vf-select>
							<vf-select name="defaultRegion" label="Bundesland" url="/api/region" nullable></vf-select>
							<vf-checkbox name="default_keepdata" label="Datenweiterverwendung" help="Standardeinstellung für Datenweiterverwendung beim anlegen neuer Mitglieder"></vf-checkbox>
							<vf-checkbox name="default_sendnewspaper" label="Zeitschriftenversand" help="Standardeinstellung für Zeitschriftenversand beim anlegen neuer Mitglieder"></vf-checkbox>
						</panelcontent>
						<panelcontent index="1">
							<vf-files name="files" label="Logo im Header" uploadurl="/api/file"></vf-files>
							<vf-text name="letterKontoName" value="Kontoinhaber"></vf-text>
							<vf-text name="letterIban" label="IBAN"></vf-text>
							<vf-text name="letterBic" label="BIC"></vf-text>
							<vf-text name="letterZweck" label="Verwendungszweck" :info="'Verwende [name] als Platzhalter für Mitglieds-Name'"></vf-text>
							<vf-checkbox name="includeFamilies" label="Familien standardmäßig zusammenführen" :info="'Familienmitglieder, die in einem Haushalt leben, bekommen standardmäßig nur eine Rechnung'"></vf-checkbox>
							<label>Standard-Deadline</label>
							<div class="row">
								<div class="col-md-6"><vf-text name="deadlinenr"></vf-text></div>
								<div class="col-md-6"><vf-select name="deadlineunit" url="/api/unit/date"></vf-select></div>
							</div>
						</panelcontent>
						<vf-submit></vf-submit>
					</panel>
				</article>
			</grid>
		</vf-form>
	</div>
</template>

<style>
	.cp-config-index form {
		height: 100%;
	}
</style>

<script>
	import {mapState} from 'vuex';

	export default {
		computed: mapState(['config']),
		components: {
			grid: require('z-ui/grid/grid.vue'),
			panel: require('z-ui/panel/panel.vue'),
			paneltab: require('z-ui/panel/tab.vue'),
			panelcontent: require('z-ui/panel/content.vue'),
			vfFiles: require('z-ui/form/fields/file.vue')
		},
		methods: {
			updateconfig(sended, response) {
				this.$store.commit('updateconfig', response);
			}
		}
	}
</script>
