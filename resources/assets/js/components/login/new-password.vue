<template>
	<div>
		<heading title="Neues Passwort erstellen"></heading>
		Geben Sie hier die E-Mail-Adresse Ihres Zugangs ein. Daraufhin werden wir eine E-Mail an diese Adresse senden, wo Sie Ihr Passwort zurücksetzen können.
		<br><br>
		<vf-form action="/password/email" redirect="login" method="post" msg="E-Mail wurde erfolgreich versendet." ajax>
			<vf-text name="email" label="E-Mail-Adresse" help="Die E-Mail-Adresse Ihres Zugangs"></vf-text>
			<vf-submit>Passwort anfordern</vf-submit>
			<v-link route="login">Zurück</v-link>
		</vf-form>
	</div>
</template>
