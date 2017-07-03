<template>
	<div class="cp-wrap cp-wrap-user-edit">
		<heading title="Benutzer bearbeiten"></heading>
		<vf-form method="patch" :url="'/api/user/'+$route.params.id" :action="'/api/user/'+$route.params.id" msg="Benutzer erfolgreich bearbeitet" redirect="user.index">
			<vf-text name="name" help="Name des Benutzers" label="Name"></vf-text>
			<vf-text name="email" help="E-Mail-Adresse des Benutzers" label="E-Mail-Adresse"></vf-text>

			<vf-select name="usergroup" url="/api/usergroup" label="Benutzergruppe"></vf-select>

			<vf-password name="password" label="Passwort" help="Passwort für den Benutzer"></vf-password>
			<vf-password name="password_confirmation" label="Passwort widerholen" help="Passwort für den Benutzer bestätigen"></vf-password>
			<vf-submit></vf-submit>
		</vf-form>
	</div>
</template>

