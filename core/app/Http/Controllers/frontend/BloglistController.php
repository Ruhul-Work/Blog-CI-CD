<?php
namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class BloglistController extends Controller
{

    public function index()
    {
        $categories = Category::whereHas('blogs', function ($query) {
            $query->where('publish_status', 'published')
                ->where('status', 1);
        })
            ->select('id', 'name', 'slug')
            ->inRandomOrder()
            ->limit(7)
            ->get();

        // Pass categories to the view
        return view('frontend.modules.blogs.blog_list', [
            'categories' => $categories,
        ]);
    }


    public function fetchAllBlogs(Request $request)
    {
        $blogsQuery = Blog::with(['author', 'categories:id,name'])
            ->where('publish_status', 'published')
            ->where('status', 1); // Apply filters to ensure only active & published blogs

        // Apply filters for sorting, category, blog type, and date
        if ($request->has('sort_by') && $request->sort_by !== 'all') {
            switch ($request->sort_by) {
                case 'latest':
                    $blogsQuery->orderBy('created_at', 'desc');
                    break;
                case 'popular':
                    $blogsQuery->orderBy('total_views', 'desc');
                    break;
                case 'most_commented':
                    $blogsQuery->orderBy('comments_count', 'desc');
                    break;
                case 'premium':
                    $blogsQuery->where('blog_type', true);
                    break;
                case 'free':
                    $blogsQuery->where('blog_type', false);
                    break;
            }
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $blogsQuery->whereHas('categories', function ($query) use ($request) {
                $query->where('slug', $request->category);
            });
        }

        if ($request->filled('blog_type')) {
            $isPremium = $request->blog_type === 'premium';
            $blogsQuery->where('blog_type', $isPremium);
        }

        if ($request->filled('selectedDate')) {
            $blogsQuery->whereDate('created_at', $request->selectedDate);
        }

        $blogs = $blogsQuery->paginate(10);

        $categories = Category::whereHas('blogs', function ($query) {
            $query->where('publish_status', 'published')
                ->where('status', 1);
        })->select('id', 'name', 'slug')->get();

        $renderedBlogs = $blogs->map(function ($blog) {
            return view('components.frontend.blog-card', ['blog' => $blog])->render();
        });

        return response()->json([
            'success'    => true,
            'blogs'      => $renderedBlogs,
            'categories' => $categories,
            'pagination' => $blogs->links('frontend.modules.pagination.custom_paginate')->render(),
            'meta'       => [
                'current_page' => $blogs->currentPage(),
                'per_page'     => $blogs->perPage(),
                'total'        => $blogs->total(),
            ],
        ]);
    }



    public function showCategoryBlogs($slug)
    {
        // Get the category
        $category = Category::where('slug', $slug)->firstOrFail();

        // Fetch only published and active blogs under this category
        $blogs = $category->blogs()
            ->where('publish_status', 'published')
            ->where('status', 1)
            ->with(['author', 'categories'])
            ->paginate(10);

        // Fetch categories that contain at least one published & active blog
        $categories = Category::whereHas('blogs', function ($query) {
            $query->where('publish_status', 'published')
                ->where('status', 1);
        })->inRandomOrder()->take(7)->get();

        return view('frontend.modules.blogs.category_blogs', [
            'blogs'      => $blogs,
            'category'   => $category,
            'categories' => $categories,
        ]);
    }



    public function showTagBlogs($slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $blogs = $tag->blogs()
            ->where('publish_status', 'published')
            ->where('status', 1)
            ->with(['author', 'categories'])
            ->paginate(10);

        $categories = Category::all();

        return view('frontend.modules.blogs.tags_blogs', [
            'tag'        => $tag,
            'blogs'      => $blogs,
            'categories' => $categories,
        ]);
    }

    // Fetch only Free Blogs
    public function freeBlogs()
    {
        $blogs = Blog::with(['author', 'categories'])
            ->where('blog_type', 0) // 0 = Free
            ->where('publish_status', 'published')
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('frontend.modules.blogs.blog_type', [
            'blogs'     => $blogs,
            'pageTitle' => 'ফ্রি ব্লগ',

        ]);
    }

    // Fetch only Premium Blogs
    public function premiumBlogs()
    {
        $blogs = Blog::with(['author', 'categories'])
            ->where('blog_type', 1) // 1 = Premium
            ->where('publish_status', 'published')
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('frontend.modules.blogs.blog_type', [
            'blogs'     => $blogs,
            'pageTitle' => 'প্রিমিয়াম ব্লগ',

        ]);
    }

}
