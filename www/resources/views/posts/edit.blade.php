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
                <h1>Edit Post</h1>
                {!! Form::open(['action' => ['PostsController@update', $post->id],'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                <div class="form-group">
                    {{Form::label('title', 'Title')}}
                    {{Form::text('title', $post->title, ['class' => 'form-control', 'placeholder' => 'Title'])}}
                </div>
                <div class="form-group ">
                    {{Form::label('body', 'Body')}}
                    {{Form::textarea('body', $post->body, ['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Body Text'])}}
                </div>
                <div class="form-group">
                    {{Form::file('cover_image')}}
                </div>
                {{Form::hidden('_method','PUT')}}
                {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}

                <a href="/posts" class="btn btn-secondary float-right">Cancel</a>
                {!! Form::close() !!}

                <hr>
            </div>
        </div>
    </div>

    <hr>


@endsection


