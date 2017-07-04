<template>
	<div class="cp-member cp-wrap">
		<heading title="Mitglieder"></heading>

		<button-bar>
			<v-link route="member.add" right="member.manage" add></v-link>
		</button-bar>

		<v-table delete-msg="Mitglied erfolgreich gelöscht">
		
		</v-table>
	</div>
</template>
