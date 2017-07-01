<template>
	<div class="cp-wrap cp-profile-index">
		<heading title="Profil bearbeiten"></heading>
		
		<vf-form msg="Update erfolgreich" :url="'/api/user/'+$user.id" method="patch" :action="'/api/user/'+$user.id" ajax>
			<vf-text name="name" label="Name" help="Hier kannst du deinen Namen ändern"></vf-text>
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
