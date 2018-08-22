@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <header class="masthead" style="background-image: url('img/Ducks-in-a-Row-1.jpg')">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-10 mx-auto">
                    <div class="site-heading">
                        <h1>PatoCO.</h1>
                        <span class="subheading">Thanks for visiting local.pato.net</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                <h1>{{$title}}</h1>
                @if(count($services)>0)
                    <ul class="list-group">
                        @foreach($services as $service)
                            <li class="list-group-item">{{$service}}</li>
                        @endforeach
                    </ul>
                @endif
                <hr>
            </div>
        </div>
    </div>

    <hr>


@endsection
