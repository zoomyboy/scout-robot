<template>
	<div class="cp-wrap cp-usergroup-index">
		<heading title="Benutzergruppen"></heading>

		<buttonbar>
			<v-link route="usergroup.add" add></v-link>
		</buttonbar>

		<v-table :headings="[{title: 'Name', data: 'title'}]" url="/api/usergroup" deletemsg="Benutzergruppe erfolgreich gelöscht" controller="usergroup">
		
		</v-table>
	</div>
</template>
