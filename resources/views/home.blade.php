@extends('layouts.app')

@section('content')
<div id="app">
	<sidebar header-href="/" header-title="Scout Robot" sub-header-title="" close-title="Sidebar schließen">
		<sidebarmenu title="Hauptmenü" :entries="entries"></sidebarmenu>
		<sidebarfooter :entries="entryfooter"></sidebarfooter>
	</sidebar>
	<comp>
		<topbar>
			<dropdown slot="right" :title="$user.name" align="right">
				<dropdown-link route="profile.index" title="Profil bearbeiten" icon="user"></dropdown-link>
				<dropdown-link route="profile.password" title="Passwort ändern" icon="shield"></dropdown-link>
				<dropdown-link href="/logout" title="Ausloggen" icon="sign-out"></dropdown-link>
			</dropdown>
		</topbar>
		<app-heading></app-heading>
		<div class="container-fluid">
			<status-bar></status-bar>
			<router-view></router-view>
		</div>
	</comp>
</div>
@endsection

@section('scripts')
	<script src="/js/app.js"></script>
@endsection
