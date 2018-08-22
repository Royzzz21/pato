@extends('layouts.admin.app')

@section('content')
    <!-- Page Header -->
    <div class="container">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Posts</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

            @if(count($posts)> 0)
            {{ session('status') }}
                @foreach($posts as $post )
                    <tr>
                        <td><a href="#">{{$post->title}}</a></td>
                        <td><a href="/admin/board/{{$post->id}}/edit" class="btn btn-secondary">Edit</a></td>
                        <td>{!!Form::open(['action' => ['AdminBoardController@destroy', $post->id], 'method' => 'POST', 'class' => 'float-right'])!!}
                            {{Form::hidden('_method', 'DELETE')}}
                            {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
                            {!!Form::close()!!}</td>
                    </tr>

                @endforeach
                    </div>
                        <div class="row justify-content-center">
                           {{$posts->links()}}
                    </div>
                     @else
                         <p>No posts found</p>
             @endif
             </tbody>
                </table>
        </div>
        <!-- /.table-responsive -->
    </div>
    <!-- /.panel-body -->
    </div>
    <!-- /.panel -->
    </div>
@endsection
