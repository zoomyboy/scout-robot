<template>
	<div>
		<heading title="Einloggen"></heading>
		Herzlich Willkommen bei Scout Robot. Bitte gib deine Logindaten ein.
		<br><br>
		<vf-form action="/login" method="post" ajax follow>
			<vf-text name="email" label="E-Mail-Adresse" help="Gib hier die E-Mail-Adresse deines Zugangs ein."></vf-text>
			<vf-password name="password" label="Passwort"  help="Gib hier das Passwort deines Zugangs ein."></vf-password>
			<vf-submit>Einloggen</vf-submit>
			<v-link route="newpw">Passwort vergessen</v-link>
		</vf-form>
	</div>
</template>
