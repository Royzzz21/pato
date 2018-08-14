<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
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

    <!-- Morris Chart Css-->
	<link href="{{ asset('res/admin/plugins/morrisjs/morris.css') }}" rel="stylesheet">

    <!-- Custom Css -->
	<link href="{{ asset('res/admin/css/style.css') }}" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
	<link href="{{ asset('admin') }}" rel="stylesheet">
</head>


<body class="login-page signup-page fp-page ls-closed">

    <div id="app" class="login-box">
        @yield('content')
    </div>

	<!-- Jquery Core Js -->
	<script src="{{ asset('res/admin/plugins/jquery/jquery.min.js') }}"></script>
	
    <!-- Bootstrap Core Js -->
	<script src="{{ asset('res/admin/plugins/bootstrap/js/bootstrap.js') }}"></script>

    <!-- Waves Effect Plugin Js -->
	<script src="{{ asset('res/admin/plugins/node-waves/waves.js') }}"></script>

	<!-- Validation Plugin Js -->
	<script src="{{ asset('res/admin/plugins/jquery-validation/jquery.validate.js') }}"></script>
	
    <!-- Custom Js -->
	<script src="{{ asset('res/admin/js/admin.js') }}"></script>
	<script src="{{ asset('res/admin/js/pages/examples/sign-in.js') }}"></script>
</body>
</html>
