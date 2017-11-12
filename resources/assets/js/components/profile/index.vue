<template>
	<div class="cp-wrap cp-profile-index">
		<vf-form msg="Profil erfolgreich aktualisiert!" url="/api/profile" method="patch" :action="'/api/profile/'+user.id" @afterpersist="updateStore">
			<vf-text name="name" label="Name" help="Hier kannst du deinen Namen ändern"></vf-text>
			<vf-text name="email" label="E-Mail-Adresse" help="Hier kannst du deine E-Mail-Adresse ändern"></vf-text>
			<vf-submit></vf-submit>
		</vf-form>
	</div>
</template>


<script>
	import {mapState} from 'vuex';

	export default {
		computed: mapState(['user']),
		methods: {
			updateStore(u) {
				this.$store.commit('updateuser', u);
			}
		}
	};
</script>
