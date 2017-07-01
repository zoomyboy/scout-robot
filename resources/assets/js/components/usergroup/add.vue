<template>
	<div class="cp-wrap cp-usergroup-add">
		<heading title="Benutzergruppe hinzufügen"></heading>

		<vf-form action="/api/usergroup" method="post" redirect="usergroup.index" msg="Benutzergruppe angelegt!" ajax>
			<vf-text name="title" label="Name"></vf-text>
			<vf-checkboxes url="/api/right" label="Rechte" name="rights"></vf-checkboxes>

			<vf-submit></vf-submit>
		</vf-form>
	</div>

</template>
