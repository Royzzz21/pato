<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" style="overflow-y: scroll;">
<head>
	<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>PATOCO</title>

	<!--Bootstrap-->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!--Costum CSS-->
	<link href="{{ asset('res/admin/css/admin.css') }}" rel="stylesheet">
    <link href="{{ asset('res/admin/css/metisMenu.min.css') }}" rel="stylesheet">


    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>



	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">






	<!--[if lt IE 9]>

	<![endif]-->
	<script>
		// import Echo from "laravel-echo";

		// window.Echo = new Echo({
			// broadcaster: 'socket.io',
			// host: window.location.hostname + ':8080'
		// });

	  // const socket = io("http://{{ Request::getHost() }}:8080");
	</script>
	<!--

	<script>
	$(document).ready(function(){
		// 소켓
		var socket           = io.connect('http://192.168.2.32:8080', {'sync disconnect on unload' : true});
	});
	</script>
	-->
</head>


<body>
<div id="wrapper">

	<!-- Navigation -->

    @include('layouts.admin.module.navbar')
	<div id="page-wrapper">

        @yield('content')
	</div>






<!-- /#wrapper -->
	<!-- Scripts

	-->
    <!-- Latest compiled and minified JavaScript -->

    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="{{ asset('js/metisMenu.min.js') }}"></script>


</body>
</html>
