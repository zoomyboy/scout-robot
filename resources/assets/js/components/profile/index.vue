<template>
	<div class="cp-wrap cp-profile-index">
		<heading title="Profil bearbeiten"></heading>
		
		<vf-form msg="Update erfolgreich" url="/api/profile" method="patch" :action="'/api/profile/'+$user.id" ajax>
			<vf-text name="name" label="Name" help="Hier kannst du deinen Namen ändern"></vf-text>
			<vf-text name="email" label="E-Mail-Adresse" help="Hier kannst du deine E-Mail-Adresse ändern"></vf-text>
			<vf-submit></vf-submit>
		</vf-form>
	</div>
</template>


<script>
	export default {
		computed: {
		}
	};
</script>
