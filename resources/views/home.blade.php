@extends('layouts.app')

@section('content')
	<div id="app">
		<comp v-if="user != false" footer>
			<div slot="sidebar" class="sidebar-container">
				<sidebar headerhref="/" headertitle="Scout Robot" subheadertitle="" closetitle="Sidebar schließen" footer>
					<ul slot="footer">
						<footerlink route="config.index" tooltip="Konfiguration" icon="cog"></footerlink>
						<footerlink route="user.index" tooltip="Benutzer bearbeiten" icon="user"></footerlink>
						<footerlink href="/logout" tooltip="Ausloggen" icon="sign-out"></footerlink>
					</ul>
				</sidebar>
			</div>
			<div slot="topbar" id="topbar-container">
				<topbar>
					<dropdown slot="right" :title="user.name" align="right">
						<dropdownlink route="profile.index" title="Profil bearbeiten" icon="user"></dropdownlink>
						<dropdownlink route="profile.password" title="Passwort ändern" icon="shield"></dropdownlink>
						<dropdownlink href="/logout" title="Ausloggen" icon="sign-out"></dropdownlink>
					</dropdown>
				</topbar>
			</div>
			<div slot="header" id="heading-container">
				<router-view name="heading"></router-view>
			</div>
			<div slot="content">
				<statusbar layout="october"></statusbar>
				<router-view></router-view>
			</div>
			<div slot="footer" id="footer-container">
				<pagefooter></pagefooter>
			</div>
		</comp>
	</div>
@endsection

@section('scripts')
	<script src="/dist/js/app.js"></script>
@endsection
