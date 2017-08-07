@extends('layouts.app')

@section('content')
	<div id="app">
		<fullpage backgroundcolor="#003c59">
			<div slot="content">
				<statusbar layout="october"></statusbar>
				<router-view></router-view>
			</div>
		</fullpage>
	</div>
@endsection

@section('scripts')
	<script src="/js/login.js"></script>
@endsection
