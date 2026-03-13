<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Product;

class BlogController extends Controller
{
    public function index()
    {
        $featuredPost = Post::query()
            ->where('status', 'published')
            ->latest('published_at')
            ->first();

        $posts = Post::query()
            ->where('status', 'published')
            ->when($featuredPost, fn ($query) => $query->where('id', '!=', $featuredPost->id))
            ->latest('published_at')
            ->paginate(6);

        return view('storefront.blog', [
            'featuredPost' => $featuredPost,
            'posts' => $posts,
            'recommendedProducts' => Product::query()->where('is_active', true)->inRandomOrder()->limit(4)->get(),
        ]);
    }

    public function show(Post $post)
    {
        abort_unless($post->status === 'published', 404);

        return view('storefront.blog-show', [
            'post' => $post,
            'latestPosts' => Post::query()
                ->where('status', 'published')
                ->where('id', '!=', $post->id)
                ->latest('published_at')
                ->limit(4)
                ->get(),
            'recommendedProducts' => Product::query()->where('is_active', true)->inRandomOrder()->limit(3)->get(),
        ]);
    }
}
