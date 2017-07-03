<template>
	<div class="cp-wrap cp-profile-index">
		<heading title="Passwort ändern"></heading>
		
		<vf-form msg="Passwort erfolgreich geändert." method="patch" :action="'/api/profile/'+$user.id+'/password'" ajax>
			<vf-password name="password" label="neues Passwort" help="Hier kannst du ein neues Passwort vergeben."></vf-password>
			<vf-password name="password_confirmation" label="neues Passwort wiederholen" help="Du solltest dein neues Passwort wiederholen."></vf-password>
			<vf-submit>Paswort speichern</vf-submit>
		</vf-form>
	</div>
</template>
