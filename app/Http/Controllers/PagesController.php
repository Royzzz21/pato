<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(){
      $title = 'Welcome To Pato!';
      //return view('pages.index', compact('title'));
      return view('pages.index')->with('title',$title);

    }
    public function about(){
      $title = 'About Us';
      return view('pages.about')->with('title',$title);
    }
    public function services(){
      $data = array(
        'title' => 'Services',
        'services' => ['PatoCo Chairman - Gerold','PHP master - MR.Tomas ','Server master - MR.Tomas','Korean Movie master - MR.Jo', 'PM-2 master - MR.G']
      );
      return view('pages.services')->with($data);
    }
}
