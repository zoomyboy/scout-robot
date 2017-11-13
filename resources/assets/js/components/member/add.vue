<template>
	<div class="cp-wrap cp-member-add">
		<vf-form redirect="member.index" method="post" action="/api/member"  msg="Mitglied erfolgreich hinzugefügt">
			<tabs>
				<tab title="Stamdaten" active>
					<vf-select url="/api/gender" name="gender" label="Geschlecht"></vf-select>
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
						<div class="col-md-6">
							<vf-select name="country" url="/api/country" label="Staatsangehörigkeit" :value="config.default_country"></vf-select>
						</div>
						<div class="col-md-6">
							<vf-select name="region" url="/api/region" label="Bundesland" :value="config.default_region"></vf-select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6"><vf-text name="phone" label="Telefonnummer" :mask="{mask: '+99 999 9{1,}'}" value="+49"></vf-text></div>
						<div class="col-md-6"><vf-text name="mobile" label="Handynummer" :mask="{mask: '+99 999 9{1,}'}" value="+49"></vf-text></div>
					</div>
					<vf-text name="email" label="E-Mail-Adresse"></vf-text>
				</tab>
				<tab title="Sonstiges">
					<vf-text name="nickname" label="Spitzname"></vf-text>
					<vf-text name="other_country" label="Andere Staatsangehörigkeit"></vf-text>
					<vf-text name="business_phone" label="Geschäftliche Nummer" :mask="{mask: '+99 999 9{1,}'}" value="+49"></vf-text>
					<vf-text name="fax" label="Fax"></vf-text>
					<vf-text name="email_parents" label="E-Mail-Adresse Erziehungsberechtigter"></vf-text>
					<vf-select name="confession" label="Konfession" url="/api/confession" nullable></vf-select>
					<vf-checkbox :value="config.default_keepdata" label="Datenweiterverwendung" name="keepdata" help="Wenn dieses Feld aktiviert wird, wird ein Mitglied beim Löschen zu den abgemeldeten Mitgliedern hinzugefügt, sodass dessen Daten noch eingesehen werden können. Wird das Mitglied mit NaMi synchronisiert, wird der Status dort auf 'inaktiv' gesetzt und auch dort bleiben die Daten bestehen.<br>Ist dieses Feld deaktiviert, werden die Daten komplett gelöscht.<br>Der Standardwert kann allgemein unter der globalen Konfiguration eingestellt werden."></vf-checkbox>
					<vf-checkbox :value="config.default_sendnewspaper" label="Zeitschriftenversand" name="sendnewspaper" help="Wenn dieses Feld aktiviert wird, bekommt ein Mitglied die Mittendrin-Zeitschrift zugesendet. Der Standardwert kann allgemein unter der globalen Konfiguration eingestellt werden."></vf-checkbox>
				</tab>
			</tabs>
			
			<vf-submit></vf-submit>
		</vf-form>
	</div>
</template>

<script>
	import {mapState} from 'vuex';

	export default {
		computed: mapState(['config']),
		components: {
			tabs: require('z-ui/tab/tabs.vue'),
			tab: require('z-ui/tab/tab.vue'),
			vfDate: require('z-ui/form/fields/date.vue')
		}
	}
</script>
