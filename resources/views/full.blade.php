@extends('layouts.app')

@section('content')
	<div id="app">
		<fullpage backgroundcolor="#003c59">
			<div slot="header" id="heading-container">
				<router-view name="heading"></router-view>
			</div>
			<div slot="content" id="content-container">
				<statusbar layout="october"></statusbar>
				<router-view></router-view>
			</div>
		</fullpage>
	</div>
@endsection

@section('scripts')
	<script src="/js/full.js"></script>
@endsection
