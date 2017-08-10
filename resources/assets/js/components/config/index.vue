<template>
	<div class="cp-wrap cp-config-index">
		<heading title="Globale Konfiguration"></heading>

		<vf-form action="/api/conf/1" method="patch" url="/api/conf/1" msg="Konfiguration gespeichert">
			<vf-select name="defaultCountry" label="Land" url="/api/country" nullable></vf-select>
			<vf-select name="defaultRegion" label="Bundesland" url="/api/region" nullable></vf-select>
			<vf-checkbox name="default_keepdata" label="Datenweiterverwendung"></vf-checkbox>
			<vf-submit></vf-submit>
		</vf-form>
	</div>
</template>
