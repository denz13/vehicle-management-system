<?php

namespace App\Http\Controllers\post;

use App\Http\Controllers\Controller;
use App\Models\tbl_post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        $posts = tbl_post::paginate(10);
        return view('post.post', compact('posts'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'announcement_title' => 'required|string|max:255|unique:tbl_post,announcement_title',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $post = tbl_post::create([
                'announcement_title' => $request->announcement_title,
                'description' => $request->description,
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Post created successfully',
                'post' => $post
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPosts(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        
        $posts = tbl_post::paginate($perPage, ['*'], 'page', $page);
        
        return response()->json([
            'success' => true,
            'posts' => $posts->items(),
            'pagination' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
                'from' => $posts->firstItem(),
                'to' => $posts->lastItem()
            ]
        ]);
    }

    public function edit($id)
    {
        try {
            $post = tbl_post::findOrFail($id);
            return response()->json([
                'success' => true,
                'post' => $post
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'announcement_title' => 'required|string|max:255|unique:tbl_post,announcement_title,' . $id,
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $post = tbl_post::findOrFail($id);
            $post->update([
                'announcement_title' => $request->announcement_title,
                'description' => $request->description,
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Post updated successfully',
                'post' => $post
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $post = tbl_post::findOrFail($id);
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Post deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete post',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
