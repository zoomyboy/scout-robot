<template>
	<div>
		Herzlich Willkommen bei Scout Robot. Bitte gebe deine Logindaten ein.
		<br><br>
		<vf-form action="/login" method="post" follow>
			<vf-text name="email" label="E-Mail-Adresse" help="Gebe hier die E-Mail-Adresse deines Zugangs ein."></vf-text>
			<vf-password name="password" label="Passwort"  help="Gebe hier das Passwort deines Zugangs ein."></vf-password>
			<vf-submit>Einloggen</vf-submit>
			<v-link route="newpw">Passwort vergessen</v-link>
		</vf-form>
	</div>
</template>
