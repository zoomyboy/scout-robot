<template>
	<div>
		Gebe hier erneut deine hinterlegte E-Mail-Adrese ein, sowie zwei Mal dein neu gewähltes Passwort.
		<br><br>
		<vf-form action="/password/reset" redirect="login.index" method="post" msg="Dein Passwort wurde erfolgreich zurückgesetzt.">
			<vf-text name="email" label="E-Mail-Adresse" help="Die E-Mail-Adresse deines Zugangs"></vf-text>
			<vf-password name="password" label="Passwort" help="Dein neu gewähltes Passwort"></vf-password>
			<vf-password name="password_confirmation" label="Passwort bestätigen" help="Dein neu gewähltes Passwort zur Bestätigung"></vf-password>
			<vf-hidden name="token" :value="$route.params.token"></vf-hidden>
			<vf-submit>Passwort zurücksetzen</vf-submit>
			<v-link route="login.index">Zurück</v-link>
		</vf-form>
	</div>
</template>

<script>
	export default {
		components: {
			vfHidden: function(resolve) {
				require(['z-ui/form/fields/hidden.vue'], resolve);
			}
		}
	}
</script>
