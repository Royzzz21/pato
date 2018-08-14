<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\User;
use DB;

class AdminMemberController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

       /* $this->validate($request, [
            'name' => 'required',
            'email' => 'required'
        ]);*/

        $users = User::orderBy('id', 'desc')->paginate(20);

        return view('admin.member.list')->with('users', $users);


    }
}
