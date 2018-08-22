@extends('layouts.admin.app')

@section('content')
    <!-- Page Header -->
    <div class="container">


        <div class="col-lg-6">
            <h1 class="page-header">Patoco Member</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->

    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                DataTables Advanced Tables
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                    </thead>
                    <tbody>
            @if(count($users)> 0)
            {{ session('status') }}
                @foreach($users as $user )

                                    <tr>
                                        <td><a href="#">{{$user->name}}</a></td>
                                        <td><a href="##">{{$user->email}}</a></td>
                                    </tr>

                                  {{--  {!!Form::open(['action' => ['AdminMemberController@destroy', $user->id], 'method' => 'POST', 'class' => 'float-right'])!!}
                                    {{Form::hidden('_method', 'DELETE')}}
                                    {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
                                    {!!Form::close()!!}--}}


                @endforeach
                    </div>
                        <div class="row justify-content-center">
                      {{$users->links()}}
                    </div>
                     @else
                         <p>No posts found</p>
             @endif
             </table>
@endsection
