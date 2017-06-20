@extends('layouts.app')

@section('content')
<div id="app">
	<sidebar header-href="/" header-title="Scout Robot" sub-header-title="" close-title="Sidebar schließen">
		<sidebarmenu title="Hauptmenü" :entries="entries"></sidebarmenu>
		<sidebarfooter :entries="entryfooter"></sidebarfooter>
	</sidebar>
	<comp>
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
