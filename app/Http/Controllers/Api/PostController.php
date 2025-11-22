<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::with('author');

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->author_id) {
            $query->where('author_id', $request->author_id);
        }

        if ($request->author_name) {
            $query->whereHas('author', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->author_name . '%');
            });
        }

        if ($request->from && $request->to) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $data = $query->orderBy('created_at', 'desc')->paginate(10);

        if (count($data) > 0) {
            if ($data->total() > $data->perPage()) {
                $result = [
                    'posts' => $data->items(),
                    'pagination' => [
                        'current_page' => $data->currentPage(),
                        'per_page' => $data->perPage(),
                        'total' => $data->total(),
                        'last_page' => $data->lastPage(),
                        'links' => [
                            'first' => $data->url(1),
                            'last' => $data->url($data->lastPage()),
                            'next' => $data->nextPageUrl(),
                            'prev' => $data->previousPageUrl(),
                        ],
                    ],
                ];

            } else {
                $result = [
                    'posts' => $data->items(),
                ];
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Posts retrieved successfully',
                'data' => $result
            ], 200);

        }
        return response()->json([
            'status' => 'error',
            'message' => 'Posts not found',
            'data' => []
        ],404);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();

        $data['author_id'] = auth()->id();

        $post = Post::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Post created successfully',
            'data' => $post,
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = Post::with('author', 'comments.user')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Post retrieved successfully',
            'data' => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        if (auth()->user()->role !== 'admin' && auth()->id() !== $post->author_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = $request->validated();

        $post->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Post updated successfully',
            'data' => $post,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if (auth()->user()->role !== 'admin' && auth()->id() !== $post->author_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Post deleted'
        ]);
    }
}
