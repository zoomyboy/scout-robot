<template>
	<div class="cp-wrap cp-usergroup-index">
		<heading title="Benutzergruppen"></heading>

		<button-bar>
			<v-link route="usergroup.add" add></v-link>
		</button-bar>

		<v-table :headings="[{title: 'Name', data: 'title'}]" url="/api/usergroup" deletemsg="Benutzergruppe erfolgreich gelöscht" controller="usergroup">
		
		</v-table>
	</div>
</template>
