<template>
	<div class="cp-wrap cp-member-add">
		<vf-form redirect="member.index" method="post" action="/api/member"  msg="Mitglied erfolgreich hinzugefügt">
			<grid><article>
				<panel>
					<div slot="tabs" class="tabs">
						<tab title="Stammdaten" index="0" active></tab>
						<tab title="Sonstiges" index="1"></tab>
					</div>
					<panelcontent index="0" active>
						<vf-select url="/api/gender" name="gender" label="Geschlecht" nullable></vf-select>
						<div class="row">
							<div class="col-md-6"><vf-text name="firstname" label="Vorname"></vf-text></div>
							<div class="col-md-6"><vf-text name="lastname" label="Nachname"></vf-text></div>
						</div>
						<div class="row">
							<div class="col-md-6"><vf-date label="Geburtsdatum" name="birthday"></vf-date></div>
							<div class="col-md-6"><vf-date name="joined_at" label="Eintrittsdatum"></vf-date></div>
						</div>
						<div class="row">
							<div class="col-md-6"><vf-text name="address" label="Adresse"></vf-text></div>
							<div class="col-md-6"><vf-text name="further_address" label="Addresszusatz"></vf-text></div>
						</div>
						<div class="row">
							<div class="col-md-6"><vf-text name="zip" label="PLZ"></vf-text></div>
							<div class="col-md-6"><vf-text name="city" label="Stadt"></vf-text></div>
						</div>
						<div class="row">
							<div class="col-md-6"><vf-text name="phone" label="Telefonnummer" :mask="{mask: '+99 999 9{1,}'}" value="+49"></vf-text></div>
							<div class="col-md-6"><vf-text name="mobile" label="Handynummer" :mask="{mask: '+99 999 9{1,}'}" value="+49"></vf-text></div>
						</div>
						<vf-text name="email" label="E-Mail-Adresse"></vf-text>
						<div class="row">
							<div class="col-md-6"><vf-select name="activity" label="Tätigkeit" @change="loadgroup" :options="activities" ref="activity"></vf-select></div>
							<div class="col-md-6"><vf-select name="group" label="Abteilung" :options="loadedGroups"></vf-select></div>
						</div>
					</panelcontent>
					<panelcontent index="1">
						<vf-text name="nickname" label="Spitzname"></vf-text>
						<div class="row">
							<div class="col-md-6">
								<vf-select name="nationality" url="/api/nationality" label="Staatsangehörigeit" :value="config.default_nationality" nullable></vf-select>
							</div>
							<div class="col-md-6">
								<vf-text name="other_country" label="Andere Staatsangehörigkeit"></vf-text>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<vf-select name="region" url="/api/region" label="Bundesland" :value="config.default_region" nullable></vf-select>
							</div>
							<div class="col-md-6">
								<vf-select name="country" url="/api/country" label="Land" :value="config.default_country"></vf-select>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6"><vf-text name="business_phone" label="Geschäftliche Nummer" :mask="{mask: '+99 999 9{1,}'}" value="+49"></vf-text></div>
							<div class="col-md-6"><vf-text name="fax" label="Fax"></vf-text></div>
						</div>
						<vf-text name="email_parents" label="E-Mail-Adresse Erziehungsberechtigter"></vf-text>
						<div class="row">
							<div class="col-md-6"><vf-select name="way" label="Rechnung versenden über..." url="/api/way"></vf-select></div>
							<div class="col-md-6"><vf-select name="confession" label="Konfession" url="/api/confession" nullable></vf-select></div>
						</div>
						<vf-checkbox :value="config.default_keepdata" label="Datenweiterverwendung" name="keepdata" help="Wenn dieses Feld aktiviert wird, wird ein Mitglied beim Löschen zu den abgemeldeten Mitgliedern hinzugefügt, sodass dessen Daten noch eingesehen werden können. Wird das Mitglied mit NaMi synchronisiert, wird der Status dort auf 'inaktiv' gesetzt und auch dort bleiben die Daten bestehen.<br>Ist dieses Feld deaktiviert, werden die Daten komplett gelöscht.<br>Der Standardwert kann allgemein unter der globalen Konfiguration eingestellt werden."></vf-checkbox>
						<vf-checkbox :value="config.default_sendnewspaper" label="Zeitschriftenversand" name="sendnewspaper" help="Wenn dieses Feld aktiviert wird, bekommt ein Mitglied die Mittendrin-Zeitschrift zugesendet. Der Standardwert kann allgemein unter der globalen Konfiguration eingestellt werden."></vf-checkbox>
					</panelcontent>
					<vf-submit></vf-submit>
				</panel>
			</article></grid>
		</vf-form>
	</div>
</template>

<style type="less">
	.cp-member-add form, .cp-member-add .form-container {
		height: 100%;
	}
	.form-container form:last-child {
		display: none;
	}
</style>

<script>
	import {mapState} from 'vuex';

	export default {
		data: function() {
			return {
				activities: [],
				loadedActivityId: null
			}
		},
		computed: {
			loadedGroups: function() {
				var vm = this;

				if (this.loadedActivityId == null) {
					return [];
				} 
			
				var filteredActivities = this.activities.filter(function(a) {
					return a.id == vm.loadedActivityId;
				});

				if (filteredActivities.length == 0) {
					return [];
				}

				return filteredActivities.shift().groups;

			},
			...mapState(['config'])
		},
		components: {
			tabs: require('z-ui/panel/tabs.vue'),
			tab: require('z-ui/panel/tab.vue'),
			panel: require('z-ui/panel/panel.vue'),
			grid: require('z-ui/grid/grid.vue'),
			vfDate: require('z-ui/form/fields/date.vue'),
			panelcontent: require('z-ui/panel/content.vue'),
		},
		methods: {
			loadgroup: function(v) {
				this.loadedActivityId = v;
			}
		},
		mounted: function() {
			var vm = this;

			axios.get('/api/activity').then((ret) => {
				vm.activities = ret.data;
			});
		}
	}
</script>
