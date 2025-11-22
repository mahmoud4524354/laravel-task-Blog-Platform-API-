<?php

namespace App\Http\Middleware;

use App\Models\Post;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPostOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $post = Post::findOrFail($request->route('post'));

        if ($request->user()->role === 'admin' || $post->author_id === $request->user()->id) {
            return $next($request);
        }

        return response()->json(['message' => 'You are not allowed'], 403);    }
}
