@extends('layouts.app')

@section('content')

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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <a href="/posts/create" class="btn btn-primary">Create Post</a>

                        <h3 style="margin-top: 1em;">Your Blog Post</h3>
                        @if(count($posts) > 0)
                            <table class="table table-striped">
                                <tr>
                                    <th>Title</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                @foreach($posts as $post)
                                    <tr>
                                        <td>{{$post->title}}</td>
                                        <td><a href="/posts/{{$post->id}}/edit" class="btn btn-secondary">Edit</a></td>
                                        <td>
                                            {!!Form::open(['action' => ['PostsController@destroy', $post->id], 'method' => 'POST', 'class' => 'float-right'])!!}
                                            {{Form::hidden('_method', 'DELETE')}}
                                            {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
                                            {!!Form::close()!!}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        @else
                            <p>You have no posts...</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>

@endsection
