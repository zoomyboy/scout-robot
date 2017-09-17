<template>
	<div class="cp-member cp-wrap">
		<heading title="Mitglieder"></heading>

		<buttonbar>
			<v-link route="member.add" right="member.manage" add></v-link>
		</buttonbar>

		<v-table controller="member" :headings="headings" url="/api/member" delete-msg="Mitglied erfolgreich gelöscht">
		
		</v-table>
	</div>
</template>

<script>
	export default {
		data: function() {
			return {
				headings: [
					{title: 'Nachname', data: 'lastname'},
					{title: 'Vorname', data: 'firstname'},
					{title: 'Adresse', data: 'address'},
					{title: 'PLZ', data: 'zip'},
					{title: 'Stadt', data: 'city'},
					{title: 'Eintritt', data: 'joined_at', type: 'date'}
				]
			};
		}
	}
</script>

