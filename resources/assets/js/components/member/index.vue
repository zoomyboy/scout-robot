<template>
	<div class="cp-member cp-wrap">
		<heading title="Mitglieder"></heading>

		<buttonbar>
			<v-link route="member.add" right="member.manage" add></v-link>
		</buttonbar>

		<v-table delete-msg="Mitglied erfolgreich gelöscht">
		
		</v-table>
	</div>
</template>
