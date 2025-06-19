<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComunitiesRequest;
use App\Models\Post_Comumunity;
use App\Models\Image_Post_Comumunity;
use App\Models\Video_Post_Comumunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post_Comumunity::with(['user', 'images', 'videos', 'comments', 'likes'])
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Posts retrieved successfully',
            'data' => $posts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ComunitiesRequest $request)
    {
        try {
            // Create post
            $post = Post_Comumunity::create([
                'content' => $request->content,
                'user_id' => Auth::id(),
            ]);

            // Handle image if provided
            if ($request->has('image')) {
                Image_Post_Comumunity::create([
                    'image' => $request->image,
                    'post__comumunity_id' => $post->id,
                    'user_id' => Auth::id()
                ]);
            }

            // Handle video if provided
            if ($request->has('video')) {
                Video_Post_Comumunity::create([
                    'video' => $request->video,
                    'post__comumunity_id' => $post->id,
                    'user_id' => Auth::id()
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Post created successfully',
                'data' => [
                    'post' => $post->load(['images', 'videos'])
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post_Comumunity::with(['user', 'images', 'videos', 'comments', 'likes'])
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Post retrieved successfully',
            'data' => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ComunitiesRequest $request, string $id)
    {
        try {
            $post = Post_Comumunity::findOrFail($id);

            // Check if user owns the post
            if ($post->user_id !== Auth::id()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ], 403);
            }

            $post->update([
                'content' => $request->content
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Post updated successfully',
                'data' => $post
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $post = Post_Comumunity::findOrFail($id);

            // Check if user owns the post
            if ($post->user_id !== Auth::id()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ], 403);
            }

            $post->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Post deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete post',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
