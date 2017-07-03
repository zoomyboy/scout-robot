@extends('layouts.app')

@section('content')
	<div id="app">
		<fullpage backgroundcolor="#003c59">
			<status-bar layout="october"></status-bar>
			<router-view></router-view>
		</fullpage>
	</div>
@endsection

@section('scripts')
	<script src="/js/login.js"></script>
@endsection
