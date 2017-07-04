<template>
	<div class="cp-wrap cp-member-add">
		<heading title="Mitglied hinzufügen"></heading>

		<vf-form method="post" action="/api/member"  msg="Mitglied erfolgreich hinzugefügt" ajax>
			<vf-text name="firstname" label="Vorname"></vf-text>
			<vf-text name="lastname" label="Nachname"></vf-text>
			<vf-text name="nickname" label="Nachname"></vf-text>
			<vf-date name="birthday" label="Geburtsdatum"></vf-date>
			<vf-submit></vf-submit>
		</vf-form>
	</div>
</template>
