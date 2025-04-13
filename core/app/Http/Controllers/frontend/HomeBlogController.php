<?php
namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Comment;
use App\Models\HomeBanner2;
use App\Models\HomeBanner1;
use App\Models\NewsLetter;
use App\Models\Point;
use App\Models\SubscriptionPackage;
use App\Models\Tag;
use Illuminate\Http\Request;

class HomeBlogController extends Controller
{

    public function index()
    {
        // Fetch the latest published & active blog
        $blog = Blog::with(['author', 'categories'])
            ->where('publish_status', 'published')
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->first();

        // Ensure blog data is never null
        $blogData = $blog ? [
            'id'             => $blog->id,
            'title'          => $blog->title,
            'slug'           => $blog->slug,
            'content'        => $blog->content,
            'blog_type'      => $blog->blog_type,
            'total_views'    => $blog->total_views,
            'likes_count'    => $blog->likes_count,
            'comments_count' => $blog->comments_count,
            'thumbnail'      => $blog->thumbnail ?? 'logo/em_blog.png', // Provide a default image
            'author'         => $blog->author ? [
                'id'   => $blog->author->id,
                'name' => $blog->author->name,
            ] : null,
            'categories'     => $blog->categories->map(function ($category) {
                return [
                    'id'   => $category->id,
                    'name' => $category->name,
                ];
            }),
            'created_at'     => $blog->created_at->toDateTimeString(),
        ] : [
            'id'             => null,
            'title'          => 'No Blog Available',
            'slug'           => '#',
            'content'        => 'There are no blogs available at the moment.',
            'blog_type'      => false,
            'total_views'    => 0,
            'likes_count'    => 0,
            'comments_count' => 0,
            'thumbnail'      => 'logo/em_blog.png',
            'author'         => null,
            'categories'     => [],
            'created_at'     => now()->toDateTimeString(),
        ];

        // Fetch only published & active blogs
        $popularPosts = Blog::select('id', 'title', 'thumbnail', 'created_at', 'blog_type', 'slug', 'likes_count')
            ->where('publish_status', 'published')
            ->where('status', 1)
            ->orderBy('likes_count', 'desc')
            ->take(3)
            ->get();

        // Fetch trending topics based on published & active blogs
        $trendingTopics = Category::whereHas('blogs', function ($query) {
            $query->where('publish_status', 'published')
                ->where('status', 1);
        }) // Ensure categories only have published & active blogs
            ->with(['blogs' => function ($query) {
                $query->select('blogs.id', 'blogs.title', 'blogs.thumbnail', 'blogs.slug', 'blogs.blog_type', 'blogs.total_views')
                    ->where('blogs.publish_status', 'published')
                    ->where('blogs.status', 1)
                    ->orderByDesc('blogs.total_views');
            }])
            ->withCount(['blogs' => function ($query) { // Count only published & active blogs
                $query->where('publish_status', 'published')
                    ->where('status', 1);
            }])
            ->select('categories.id', 'categories.name', 'categories.slug', 'categories.cover_image')
            ->take(10)
            ->get();

        //banner show
        $banner = HomeBanner2::where('status', 1)->latest()->first();

        // Fetch featured blogs (only published & active)
        $featuredBlogs = Blog::with(['author', 'categories'])
            ->where('publish_status', 'published')
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get()
            ->map(function ($blog) {
                return [
                    'id'         => $blog->id,
                    'title'      => $blog->title,
                    'blog_type'  => $blog->blog_type,
                    'slug'       => $blog->slug,
                    'thumbnail'  => $blog->thumbnail ?? 'logo/em_blog.png',
                    'categories' => $blog->categories->pluck('name')->toArray(),
                    'author'     => $blog->author->name ?? 'M.Rafique',
                    'icon'       => $blog->author->icon ?? 'theme/frontend/assets/images/user.png',
                    'views'      => $blog->total_views,
                    'likes'      => $blog->likes_count,
                    'comments'   => $blog->comments_count,
                    'created_at' => $blog->created_at->toDateString(),
                ];
            });

        // Fetch recent blogs (only published & active)
        $recentBlogs = Blog::with(['author', 'categories'])
            ->where('publish_status', 'published')
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->take(7)
            ->get();

        $detailedBlogs = $recentBlogs->take(3);
        $minimalBlogs  = $recentBlogs->skip(3)->take(4);

        $packages   = SubscriptionPackage::orderBy('created_at', 'desc')->take(3)->get();
        $categories = Category::all();
        
        
        $popUpBanner = HomeBanner1::where('status', 1)
                ->latest()
                ->first();
                

        return view('frontend.modules.blogs.index', [
            'blogs'          => $blogData,
            'popularPosts'   => $popularPosts,
            'trendingTopics' => $trendingTopics,
            'featuredBlogs'  => $featuredBlogs,
            'detailedBlogs'  => $detailedBlogs,
            'minimalBlogs'   => $minimalBlogs,
            'packages'       => $packages,
            'categories'     => $categories,
            'banner'         => $banner,
            'popup'          =>$popUpBanner,
        ]);
    }

    // Fetch the latest 8 blogs card function
    public function fetchBlogsCard()
    {
        $latestBlogs = Blog::select('id', 'title', 'thumbnail', 'blog_type', 'created_at', 'slug')
            ->with(['categories:id,name', 'author:id,name'])
            ->where('publish_status', 'published')
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $renderedBlogs = $latestBlogs->map(function ($blog) {
            return view('components.frontend.blog-card', ['blog' => $blog])->render();
        });

        return response()->json([
            'success' => true,
            'blogs'   => $renderedBlogs,
        ]);
    }

    public function show(Request $request, $slug)
    {
        // Fetch the blog by slug
        $blog = Blog::with(['author', 'categories', 'tags'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Check if the blog is premium
        $isPremium = $blog->blog_type === true;
        //🔹🔹🔹Check if user has an active subscription🔹🔹🔹
        $userHasSubscription = auth()->check() && auth()->user()->hasActiveSubscription();

        if (! $isPremium) {
            $userHasSubscription = true;
        }

        $blog->incrementTotalViews();

        // Fetch related data
        $popularBlogs = Blog::orderBy('total_views', 'desc')->limit(2)->get();
        $recentBlogs  = Blog::orderBy('created_at', 'desc')->limit(2)->get();
        $categories   = Category::inRandomOrder()->take(7)->get();
        $tags         = Tag::all();
        $comments     = Comment::where('blog_id', $blog->id)->with('user')->get();

        // Check if the user liked this blog
        $userId  = auth()->id();
        $isLiked = $userId ? $blog->isLikedByUser($userId) : false;

        return view('frontend.modules.blogs.blog_details', [
            'blog'                => $blog,
            'categories'          => $categories,
            'popularBlogs'        => $popularBlogs,
            'recentBlogs'         => $recentBlogs,
            'tags'                => $tags,
            'comments'            => $comments,
            'isLiked'             => $isLiked,
            'userHasSubscription' => $userHasSubscription,
        ]);
    }

    public function dashboardIndex()
    {
        $blogsComments = Comment::count();
        $blogsShared   = Point::whereNotNull('debit')->count();
        $blogsLike     = Blog::sum('likes_count');

        $blogsComments = intval($blogsComments);
        $blogsShared   = intval($blogsShared);
        $blogsLike     = intval($blogsLike);

        return view('frontend.modules.dashboard.index', [
            'isDashboard'   => true,
            'blogsComments' => $blogsComments,
            'blogsShared'   => $blogsShared,
            'blogsLike'     => $blogsLike,
            'chartData'     => [
                'series' => [$blogsComments, $blogsShared, $blogsLike],
                'labels' => ['ব্লগ মন্তব্য', 'ব্লগ শেয়ার', 'ব্লগ পছন্দ'],
            ],
        ]);
    }

    public function suscribeNewsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $subscription        = new Newsletter();
        $subscription->email = $request->email;
        $subscription->save();

        return response()->json([
            'success' => true,
            'message' => 'আপনি সফলভাবে আমাদের নিউজলেটার সাবস্ক্রাইব করেছেন।',
        ]);
    }

    //search method
    public function search(Request $request)
    {
        $query = $request->input('query');

        $blogs = Blog::where('title', 'LIKE', "%{$query}%")
            ->where('publish_status', 'published')
            ->where('status', 1)
            ->take(5)
            ->get(['title', 'slug', 'thumbnail']);

        return response()->json($blogs);
    }


}
