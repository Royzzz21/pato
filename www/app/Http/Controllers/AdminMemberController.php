<?php

namespace App\Http\Controllers;


use Illuminate\Database\Query\Builder;
use App\User;
use App\Post;
use DB;

class AdminMemberController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $users = User::orderBy('id', 'desc')->paginate(10);
        return view('admin.member.list')->with('users', $users);


    }
    public function destroy($id){

        $users = User::find($id);
        $post = Post::find($id);

        $post->delete();
        $users->delete();
        return redirect('/admin/member')->with('success', 'Post Removed');

    }
}
