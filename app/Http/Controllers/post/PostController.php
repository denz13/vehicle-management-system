<?php

namespace App\Http\Controllers\post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    public function index()
    {
        return view('post.post');
    }

    public function getPosts()
    {
        return view('post.post');
    }

    public function createPost()
    {
        return view('post.post');
    }
    
    public function updatePost()
    {
        return view('post.post');
    }
    
    public function deletePost()
    {
        return view('post.post');
    }
    
    
}
