<template>
	<div class="cp-wrap cp-usergroup-edit">
		<heading title="Benutzergruppe hinzufügen"></heading>

		<vf-form :action="'/api/usergroup/'+$route.params.id" method="patch" redirect="usergroup.index" msg="Benutzergruppe bearbeitet!" :url="'/api/usergroup/'+$route.params.id" ajax>
			<vf-text name="title" label="Name"></vf-text>
			<vf-checkboxes url="/api/right" label="Rechte" name="rights"></vf-checkboxes>

			<vf-submit></vf-submit>
		</vf-form>
	</div>

</template>
