@extends('layouts.app')

@section('content')
    <header class="masthead" style="background-image: url('{{ asset('img/Ducks-in-a-Row-1.jpg') }}')">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-10  col-md-10 mx-auto">
                    <div class="site-heading">
                        <h1>PatoCO.</h1>
                        <span class="subheading">Thanks for visiting local.pato.net</span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!--Main section-->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                <a href="/posts" class="btn btn-primary" style="margin-bottom: 2em;">Go Back</a>
                <h1>{{$post->title}}</h1>
                <img width="auto" height="600" src="/storage/cover_images/{{$post->cover_image}}" alt="">
                <br>
                <div>
                    {!!$post->body!!}
                </div>
                <hr>
                <small>Written on {{$post->created_at}} by  {{$post->user->name}}</small>
                <hr>
                @if(!Auth::guest())
                    @if(Auth::user()->id == $post->user_id)
                        <a href="/posts/{{$post->id}}/edit" class="btn btn-primary">Edit</a>

                        {!!Form::open(['action' => ['PostsController@destroy', $post->id], 'method' => 'POST', 'class' => 'float-right'])!!}
                        {{Form::hidden('_method', 'DELETE')}}
                        {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
                        {!!Form::close()!!}
                    @endif
                @endif

            </div>
        </div>
    </div>
    <hr>

@endsection
