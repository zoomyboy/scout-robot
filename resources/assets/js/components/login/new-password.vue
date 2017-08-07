<template>
	<div>
		<heading title="Neues Passwort erstellen"></heading>
		Gebe hier die E-Mail-Adresse deines Zugangs ein. Daraufhin werden wir eine E-Mail an diese Adresse senden, wo du dein Passwort zurücksetzen kannst.
		<br><br>
		<vf-form action="/password/email" redirect="login" method="post" msg="E-Mail wurde erfolgreich versendet." ajax>
			<vf-text name="email" label="E-Mail-Adresse" help="Die E-Mail-Adresse deines Zugangs"></vf-text>
			<vf-submit>Passwort anfordern</vf-submit>
			<v-link route="login">Zurück</v-link>
		</vf-form>
	</div>
</template>
