<template>
	<div>
		Gebe hier deine hinterlegte E-Mail-Adrese ein, sowie zwei Mal dein neu gewähltes Passwort.
		<br><br>
		<vf-form action="/password/first" redirect="login" method="post" msg="Dein Zugang wurde erfolgreich erstellt." ajax>
			<vf-text name="email" label="E-Mail-Adresse" help="Die E-Mail-Adresse deines Zugangs"></vf-text>
			<vf-password name="password" label="Passwort" help="Dein neu gewähltes Passwort"></vf-password>
			<vf-password name="password_confirmation" label="Passwort bestätigen" help="Dein neu gewähltes Passwort zur Bestätigung"></vf-password>
			<vf-hidden name="token" :value="$route.params.token"></vf-hidden>
			<vf-submit>Passwort speichern</vf-submit>
		</vf-form>
	</div>
</template>
