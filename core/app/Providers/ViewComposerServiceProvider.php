<?php
namespace App\Providers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        // Share common data with specific views or all views
        View::composer(['frontend.include.blogs.header', 'frontend.include.blogs.footer'], function ($view) {
            // Header date
            $currentDate = Carbon::now();
            $headerDate  = [
                'day'  => $currentDate->format('l'),
                'date' => $currentDate->format('M d, Y'),
            ];

            // Popular posts
            $popularPosts = Blog::select('id', 'title', 'thumbnail', 'created_at', 'blog_type', 'slug')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
            // Latest 5 tags for the footer
            $popularTags = Tag::select('tags.id', 'tags.name', 'tags.slug')
                ->leftJoin('blog_tags', 'tags.id', '=', 'blog_tags.tags_id')
                ->selectRaw('COUNT(blog_tags.blog_id) as blog_count')
                ->groupBy('tags.id', 'tags.name', 'tags.slug')
                ->orderByDesc('blog_count')      
                ->orderByDesc('tags.created_at')
                ->limit(6)
                ->get();

            $view->with([
                'headerDate'   => $headerDate,
                'popularPosts' => $popularPosts,
                'popularTags'  => $popularTags,
            ]);

            // Cetagory menu main navbar
            View::composer('frontend.include.blogs.main_navbar', function ($view) {
                $categories = Category::select('id', 'name', 'slug')
                    ->where('is_menu', 1)
                    ->where('status', 1)
                    ->get();
            
                $view->with('categories', $categories);
            });

        });
    }
}
