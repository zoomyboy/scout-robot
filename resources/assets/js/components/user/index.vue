<template>
	<div class="cp-wrap-user-index cp-wrap">
		<heading title="Benutzer-Übersicht"></heading>
		<buttonbar>
			<v-link route="user.add" add></v-link>
		</buttonbar>
		<v-table url="/api/user" :headings="[{title: 'Name', data: 'name'}, {title: 'Gruppe', data: 'usergroup[title]'}]" controller="user" delete-msg="Benutzer erfolgreich gelöscht.">

		</v-table>
	</div>
</template>
