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
    <div class="container">
            @if(count($posts)> 0)
                @foreach($posts as $post )

                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                                <img width="350" height="300" src="/storage/cover_images/{{$post->cover_image}}" alt="">
                            </div>
                            <div class="col-lg-8 col-md-10">
                                <div class="post-preview">
                                    <a href="/posts/{{$post->id}}">
                                        <h2 class="post-title">
                                            {{$post->title}}
                                        </h2>

                                    </a>
                                    <p class="post-meta">Written by
                                        <a href="#">{{$post->user->name}}</a>
                                        on {{$post-> created_at}}</p>
                                </div>

                                <hr>

                            </div>
                        </div>
                <hr>

                @endforeach
                    </div>
                        <div class="row justify-content-center">
                            {{$posts->links()}}
                    </div>
                     @else
                         <p>No posts found</p>
             @endif
@endsection
