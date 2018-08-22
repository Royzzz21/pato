<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;
use DB;

class AdminBoardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        $posts = Post::orderBy('id', 'desc')->paginate(10);

        return view('admin.board.list')->with('posts', $posts);


    }
    public function edit($id)
    {
        $post = Post::find($id);

        //Check for correct user
        if (!$post) {
            return redirect()->route('notfound');
        }

        /*
           if(auth()->user()->id != $post->user_id) {
            return redirect('/posts')->with('error', 'Unauthorized Page');
        }

        */
        return view ('admin.board.edit')->with('post', $post);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);

        // Handle File Upload
        if ($request->hasFile('cover_image')) {
            // Get filename with the extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            // Upload Image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        }

        // Create Posts
        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        if ($request->hasFile('cover_image')) {
            $post->cover_image = $fileNameToStore;
        }
        $post->save();

        return redirect('/admin/board')->with('success', 'Post Updated');
    }

    public function destroy($id)
    {
        $post = Post::find($id);

         if ($post->cover_image != 'noimage.jpg') {
            // Delete Image
            Storage::delete('public/cover_images/' . $post->cover_image);
        }
        $post->delete();
        return redirect('/admin/board')->with('success', 'Post Removed');
    }
}
