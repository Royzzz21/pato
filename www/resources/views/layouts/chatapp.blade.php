<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->



    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">


    <!-- Fonts -->


    <!-- Styles -->

    <!------ Include the above in your HEAD tag ---------->

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>


    <!-- Bootstrap core JavaScript -->

    {{--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>--}}

    <style>
        }
        #custom-search-input .search-query {
            background: #fff none repeat scroll 0 0 !important;
            border-radius: 4px;
            height: 33px;
            margin-bottom: 0;
            padding-left: 7px;
            padding-right: 7px;
        }
        #custom-search-input button {
            background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
            border: 0 none;
            border-radius: 3px;
            color: #666666;
            left: auto;
            margin-bottom: 0;
            margin-top: 7px;
            padding: 2px 5px;
            position: absolute;
            right: 0;
            z-index: 9999;
        }
        .search-query:focus + button {
            z-index: 3;
        }
        .all_conversation button {
            background: #f5f3f3 none repeat scroll 0 0;
            border: 1px solid #dddddd;
            height: 38px;
            text-align: left;
            width: 100%;
        }
        .all_conversation i {
            background: #e9e7e8 none repeat scroll 0 0;
            border-radius: 100px;
            color: #636363;
            font-size: 17px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            width: 30px;
        }
        .all_conversation .caret {
            bottom: 0;
            margin: auto;
            position: absolute;
            right: 15px;
            top: 0;
        }
        .all_conversation .dropdown-menu {
            background: #f5f3f3 none repeat scroll 0 0;
            border-radius: 0;
            margin-top: 0;
            padding: 0;
            width: 100%;
        }
        .all_conversation ul li {
            border-bottom: 1px solid #dddddd;
            line-height: normal;
            width: 100%;
        }
        .all_conversation ul li a:hover {
            background: #dddddd none repeat scroll 0 0;
            color:#333;
        }
        .all_conversation ul li a {
            color: #333;
            line-height: 30px;
            padding: 3px 20px;
        }
        .member_list .chat-body {
            margin-left: 47px;
            margin-top: 0;
        }
        .top_nav {
            overflow: visible;
        }
        .member_list .contact_sec {
            margin-top: 3px;
        }
        .member_list li {
            padding: 6px;
        }
        .member_list ul {
            border: 1px solid #dddddd;
        }
        .chat-img img {
            height: 34px;
            width: 34px;
        }
        .member_list li {
            border-bottom: 1px solid #dddddd;
            padding: 6px;
        }
        .member_list li:last-child {
            border-bottom:none;
        }
        .all_conversation ul li:hover .sub_menu_ {
            display: block;
        }
        .new_message_head button {
            background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
            border: medium none;
        }
        .new_message_head {
            background: #f5f3f3 none repeat scroll 0 0;
            float: left;
            font-size: 13px;
            font-weight: 600;
            padding: 18px 10px;
            width: 100%;
        }
        .message_section {
            border: 1px solid #dddddd;
        }
        .chat_area {
            float: left;
            height: 300px;

            width: 100%;
        }
        .chat_area li {
            padding: 14px 14px 0;
        }
        .chat_area li .chat-img1 img {
            height: 40px;
            width: 100px;
        }
        .chat_area .chat-body1 {
            margin-left: 50px;
        }
        .chat-body1 p {
            background: #fbf9fa none repeat scroll 0 0;
            padding: 10px;
        }
        .chat_area .admin_chat .chat-body1 {
            margin-left: 0;
            margin-right: 50px;
        }
        .chat_area li:last-child {
            padding-bottom: 10px;
        }
        .message_write {
            background: #f5f3f3 ;
            float: left;
            padding: 15px;
            width: 100%;
        }

        .message_write textarea.form-control {
            height: 70px;
            padding: 10px;
        }
        .chat_bottom {
            float: left;
            margin-top: 13px;
            width: 100%;
        }
        .upload_btn {
            color: #777777;
        }
        .sub_menu_ > li a, .sub_menu_ > li {
            float: left;
            width:100%;
        }


    </style>


</head>
<body>

    @yield('content')

</body>
</html>
