<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Laravel') }}</title>
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
    <script src="{{ config('broadcasting.socket_host') }}/socket.io/socket.io.js"></script>

    <meta name="socket_host" content="{{ config('broadcasting.socket_host') }}">
</head>
<body>
	@yield('content')

	@yield('scripts')
</body>
</html>
