<template>
	<div class="cp-wrap-user-index cp-wrap">
		<heading title="Benutzer-Übersicht"></heading>
		<button-bar>
			<v-link route="user.add" add></v-link>
		</button-bar>
		<v-table url="/api/user" :headings="[{title: 'Name', data: 'name'}, {title: 'Gruppe', data: 'usergroup[title]'}]" controller="user" delete-msg="Benutzer erfolgreich gelöscht.">

		</v-table>
	</div>
</template>
