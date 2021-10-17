<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Cviebrock\EloquentSluggable\Services\SlugService;

use Illuminate\Http\Request;

class PostsController extends Controller
{
     //redirect users who are not logged in trying to access index and show pages back to login page   
    public function __construct()
        {
            $this->middleware('auth', ['except'=> ['index', 'show']]);
        }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$post = Post::all();
        //dd($post);
        return view('blog.index')
            ->with('posts', Post::orderBy('updated_at', 'DESC')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('blog.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //composer require cviebrock/eloquent-sluggable 
        //dd($request);
        //validate request 5048kilo bytes
        $request->validate([
            'title'=> 'required',
            'description'=>'required',
            'image'=> 'required|mimes:jpg,png,jpeg|max:5048'  
        ]);
        //generate unique id
        $newImageName = uniqid() . '-' . $request->title. '-' .
        $request->image->extension();
        $request->image->move(public_path('images'), $newImageName);
        //dd($newImageName);
 
        //second param is the colum name 'slug'
        $slug = SlugService::createSlug(Post::class, 'slug', $request->title);
        //dd($slug);

        Post::create([
            'title'=> $request->input('title'),
            'description'=> $request->input('description'),
            'slug'=> SlugService::createSlug(Post::class, 'slug', $request->title),
            'image_path'=> $newImageName,
            'user_id'=> auth()->user()->id
        ]);

        return redirect('/blog')
        ->with('message', 'Your Post has been added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return view('blog.show')
        ->with('post', Post::where('slug', $slug)->first());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        return view('blog.edit')
            ->with('post', Post::where('slug', $slug)->first());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $request->validate([
            'title'=> 'required',
            'description'=>'required'
            
        ]);
        Post::where('slug', $slug)->update([
            'title'=> $request->input('title'),
            'description'=> $request->input('description'),
            'slug'=> SlugService::createSlug(Post::class, 'slug', $request->title),
            
            'user_id'=> auth()->user()->id
        ]);

        return redirect('/blog')
        ->with('message','Your Post has been Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $post = Post::where('slug', $slug);
        $post->delete();

        return redirect('/blog')
        ->with('message','Your Post has been Deleted');

    }
}


