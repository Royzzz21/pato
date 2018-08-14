<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" style="overflow-y: scroll;">
<head>
	<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
	
    <!-- Bootstrap Core Css -->
	<link href="{{ asset('res/admin/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet">
    
    <!-- Waves Effect Css -->
	<link href="{{ asset('res/admin/plugins/node-waves/waves.css') }}" rel="stylesheet">

    <!-- Animation Css -->
	<link href="{{ asset('res/admin/plugins/animate-css/animate.css') }}" rel="stylesheet">

	<!-- Bootstrap Material Datetime Picker Css -->
    <link href="{{ asset('res/admin/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet" />
	
	<!-- Wait Me Css -->
    <link href="{{ asset('res/admin/plugins/waitme/waitMe.css') }}" rel="stylesheet" />

    <!-- Bootstrap Select Css -->
    <link href="{{ asset('res/admin/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet" />
	
    <!-- Morris Chart Css-->
	<link href="{{ asset('res/admin/plugins/morrisjs/morris.css') }}" rel="stylesheet">

    <!-- Custom Css -->
	<link href="{{ asset('res/admin/css/style.css') }}" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
	<link href="{{ asset('res/admin/css/themes/all-themes.css') }}" rel="stylesheet">
	
	<style>
	body{letter-spacing:-1px;}
	.navbar-collapse{padding-left:0 !important; padding-right:0 !important;}
	</style>
	
	<!-- Jquery Core Js -->
	<script type="text/javascript" src="{{ asset('res/admin/plugins/jquery/jquery.min.js') }}"></script>
	
	<script src="http://{{ Request::getHost() }}:8080/socket.io/socket.io.js"></script>
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


<body class="theme-blue">

	{{-- pageloader --}}
	@include('layouts.admin.module.pageloader')
	
	{{-- topbar --}}
	@include('layouts.admin.module.topbar')

	
	<section class="content">
        <div class="container-fluid">
			@yield('content')
        </div>
    </section>

	
	
	
	<!-- Moment Plugin Js -->
    <script src="{{ asset('res/admin/plugins/momentjs/moment.js') }}"></script>
	
    <!-- Bootstrap Core Js -->
	<script src="{{ asset('res/admin/plugins/bootstrap/js/bootstrap.js') }}"></script>

	<!-- Bootstrap Material Datetime Picker Plugin Js -->
    <script src="{{ asset('res/admin/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
	
    <!-- Slimscroll Plugin Js -->
	<script src="{{ asset('res/admin/plugins/jquery-slimscroll/jquery.slimscroll.js') }}"></script>

    <!-- Waves Effect Plugin Js -->
	<script src="{{ asset('res/admin/plugins/node-waves/waves.js') }}"></script>

	<!-- Autosize Plugin Js -->
    <script src="{{ asset('res/admin/plugins/autosize/autosize.js') }}"></script>
	
    <!-- Jquery CountTo Plugin Js -->
	<script src="{{ asset('res/admin/plugins/jquery-countto/jquery.countTo.js') }}"></script>

    <!-- Morris Plugin Js -->
	<script src="{{ asset('res/admin/plugins/raphael/raphael.min.js') }}"></script>
	<script src="{{ asset('res/admin/plugins/morrisjs/morris.js') }}"></script>

    <!-- ChartJs -->
	<script src="{{ asset('res/admin/plugins/chartjs/Chart.bundle.js') }}"></script>

    <!-- Flot Charts Plugin Js -->
	<script src="{{ asset('res/admin/plugins/flot-charts/jquery.flot.js') }}"></script>
	<script src="{{ asset('res/admin/plugins/flot-charts/jquery.flot.resize.js') }}"></script>
	<script src="{{ asset('res/admin/plugins/flot-charts/jquery.flot.pie.js') }}"></script>
	<script src="{{ asset('res/admin/plugins/flot-charts/jquery.flot.categories.js') }}"></script>
	<script src="{{ asset('res/admin/plugins/flot-charts/jquery.flot.time.js') }}"></script>

    <!-- Sparkline Chart Plugin Js -->
	<script src="{{ asset('res/admin/plugins/jquery-sparkline/jquery.sparkline.js') }}"></script>

    <!-- Custom Js -->
	<script src="{{ asset('res/admin/js/admin.js') }}"></script>
	<script src="{{ asset('res/admin/js/pages/forms/basic-form-elements.js') }}"></script>
	<!--<script src="{{ asset('res/admin/js/pages/index.js') }}"></script>-->

    <!-- Demo Js -->
	<script src="{{ asset('bower_components/web3/dist/web3.js') }}"></script>
	
	<script>
	if (typeof web3 !== 'undefined') {
	  web3 = new Web3(web3.currentProvider);
	  console.log('web3');
	} else {
	  // set the provider you want from Web3.providers
	  web3 = new Web3(new Web3.providers.HttpProvider("http://localhost:8545"));
	  console.log(web3);
	}
	
	// window.Vue = require('vue');
	
	// import Echo from "laravel-echo"

	// window.Echo = new Echo({
		 // broadcaster: 'pusher',
		 // key: 'your-pusher-key'
	// });
	</script>
	
	<!-- Scripts 
    <script src="{{ asset('js/app.js') }}"></script>
	-->
	
</body>
</html>
