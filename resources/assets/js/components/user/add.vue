<template>
	<div class="cp-wrap cp-wrap-user-add">
		<heading title="Benutzer hinzufügen"></heading>
		<vf-form method="post" action="/api/user" msg="Benutzer erfolgreich hinzugefügt" redirect="user.index">
			<vf-text name="name" help="Name des neuen Benutzers" label="Name"></vf-text>
			<vf-text name="email" help="E-Mail-Adresse des neuen Benutzers" label="E-Mail-Adresse"></vf-text>

			<vf-select name="usergroup" url="/api/usergroup" label="Benutzergruppe"></vf-select>

			<vf-checkbox name="nopw" label="Passwort selber setzen lassen" help="Wenn du das hier aktivierst, bekommt der Benutzer eine E-Mail zugesendet, mit der er sein Passwort selbst bestimmen kann. Erst dann kann er sich mit diesem Passwort einloggen.<br>Wenn das hier deaktiviert ist, muss dem Beenutzer sein Passwort extra mitgeteilt werden"></vf-checkbox>

			<vf-password name="password" label="Passwort" help="Passwort für den Benutzer" v-if="!nopw"></vf-password>
			<vf-password name="password_confirmation" label="Passwort widerholen" help="Passwort für den Benutzer bestätigen" v-if="!nopw"></vf-password>

			<vf-checkbox name="sendemail" label="Sende dem Benutzer sein Passwort per E-Mail zu" :value="true" v-show="!nopw" ref="fieldsendemail"></vf-checkbox>
			<vf-submit></vf-submit>
		</vf-form>
	</div>
</template>

<script>
	export default {
		data: function() {
			return {
				nopw: false
			};
		},
		mounted: function() {
			var vm = this;

			this.$events.listen('vf-checkbox-change-nopw', function(value) {
				vm.nopw = value;
				if (value == false) {
					vm.$refs.fieldsendemail.setValue(true);
				}
			});
		}
	}
</script>
