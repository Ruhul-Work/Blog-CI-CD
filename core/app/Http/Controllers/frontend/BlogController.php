<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Comment;

class BlogController extends Controller
{

    public function allBlogs()
    {


        return view('frontend.modules.blogs.index');
    }


    public function getBlogs()
    {

        $blogs = Blog::paginate(15);
        //        dd($blogs);

        $view = view('frontend.modules.blogs.blog_design', ['blogs' => $blogs])->render();

        return response()->json(['html' => $view]);
    }

    public function blogSingle(Request $request,$slug)
    {
        dd($request->all());
        
        // Attempt to find the blog by slug first
        $blog = Blog::findBySlug($slug);

        // If blog is not found by slug and the input is numeric, try finding by ID
        if (!$blog && is_numeric($slug)) {
            $blog = Blog::findOrFail((int)$slug); // Find by ID or throw 404 if not found
        }



        // If campaign not found, abort with 404
        if (!$blog) {
            abort(404);
        }

        $relatedBlogs = Blog::whereHas('categories', function ($query) use ($blog) {
            $query->whereIn('blog_categories.id', $blog->categories->pluck('id'));
        })->where('blogs.id', '!=', $blog->id)->take(2)->get();



        $blogCategories = BlogCategory::with(['blogs' => function ($query) {
            $query->orderBy('created_at', 'desc')->limit(2);
        }])->limit(5)->get();

        $mostReadBlogs = Blog::orderBy('read_count', 'desc')
            ->take(5)
            ->get();

        $allCategories = BlogCategory::all();



        $blog->read_count += 1;
        $blog->save();



       



        return view(
            'frontend.modules.blogs.single_blog',
            [
                'blog' => $blog,
                'relatedBlogs' => $relatedBlogs,
                'blogCategories' => $blogCategories,
                'allCategories' => $allCategories,
                'mostReadBlogs' => $mostReadBlogs
            ]
        );
    }




    public function showByCategory($slug)
    {


        if (is_numeric($slug)) {
            $id = $slug;
            $blogCategory = BlogCategory::findOrFail($id);
        } else {
            $blogCategory = BlogCategory::findBySlug($slug);
        }


        $blogs = $blogCategory->blogs()->paginate(10);

        return view('frontend.modules.blogs.category_blogs', [
            'blogs' => $blogs,
            'blogCategory' => $blogCategory
        ]);
    }


    public function search(Request $request)
    {
        $query = $request->input('query');

        // Perform the search on the blog title or content
        $blogs = Blog::where('title', 'like', '%' . $query . '%')
            ->orWhere('content', 'like', '%' . $query . '%')
            ->limit(6)
            ->get();

        // Return a view fragment for AJAX response
        return view('frontend.modules.blogs.search_results', [
            'blogs' => $blogs,
            'query' => $query
        ])->render(); // Render the view to HTML
    }



    public function submitComment(Request $request, $blogId)
    {

        if (!Auth::check()) {
            return response()->json(['message' => 'Please log in first to write comment'], 401);
        }

        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $comment = new BlogComment();

        $comment->blog_id = $blogId;
        $comment->comment = $validated['comment'];
        $comment->user_id = Auth::id() ?: null;
        $comment->save();

        $comment->load('user');

        $html = View::make('frontend.modules.blogs.comment_list', compact('comment'))->render();


        return response()->json(['html' => $html]);
    }
}
