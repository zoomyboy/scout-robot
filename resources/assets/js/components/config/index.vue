<template>
	<div class="cp-wrap cp-config-index">
		<vf-form :getmodel="config" method="patch" action="/api/conf/1" msg="Konfiguration gespeichert" @afterpersist="updateconfig">
			<vf-select name="defaultCountry" label="Land" url="/api/country" nullable></vf-select>
			<vf-select name="defaultRegion" label="Bundesland" url="/api/region" nullable></vf-select>
			<vf-checkbox name="default_keepdata" label="Datenweiterverwendung" help="Standardeinstellung für Datenweiterverwendung beim anlegen neuer Mitglieder"></vf-checkbox>
			<vf-checkbox name="default_sendnewspaper" label="Zeitschriftenversand" help="Standardeinstellung für Zeitschriftenversand beim anlegen neuer Mitglieder"></vf-checkbox>
			<vf-submit></vf-submit>
		</vf-form>
	</div>
</template>

<script>
	import {mapState} from 'vuex';

	export default {
		computed: mapState(['config']),
		methods: {
			updateconfig(sended, response) {
				this.$store.commit('updateconfig', response);
			}
		}
	}
</script>
