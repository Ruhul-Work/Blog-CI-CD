<?php
namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Common;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        return view('backend.modules.blog.index');
    }

    public function ajaxIndex(Request $request)
    {
        $columns    = ["id", "title", "slug", "thumbnail", "categories", "tags", "author", "blog_type", "status", "publish_status"];
        $draw       = $request->draw;
        $row        = $request->start;
        $rowperpage = $request->length;

        $columnIndex     = $request->order[0]['column'];
        $columnName      = ! empty($columns[$columnIndex]) ? $columns[$columnIndex] : $columns[0];
        $columnSortOrder = $request->order[0]['dir'];
        $searchValue     = $request->search['value'];

        $query = Blog::with(['categories', 'tags', 'author']);

        $totalRecords = $query->count();

        if (! empty($searchValue)) {
            $query->where('title', 'like', '%' . $searchValue . '%')
                ->orWhere('slug', 'like', '%' . $searchValue . '%');
        }

        $totalDisplayRecords = $query->count();
        $records             = $query->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowperpage)
            ->get();

        $data = [];
        foreach ($records as $key => $blog) {
            // Generate category badges
            $categories = $blog->categories->map(function ($category) {
                return '<span class="badge rounded-pill bg-success">' . $category->name . '</span>';
            })->join(' ');

            // Generate tag badges
            $tags = $blog->tags->map(function ($tag) {
                return '<span class="badge rounded-pill bg-info">' . $tag->name . '</span>';
            })->join(' ');
            // $categories = $blog->categories->pluck('name')->join(', ');
            // $tags = $blog->tags->pluck('name')->join(', ');
            $author    = $blog->author ? $blog->author->name : 'N/A';
            $thumbnail = $blog->thumbnail
            ? '<img src="' . asset($blog->thumbnail) . '" width="100" height="100">'
            : 'N/A';

            $row   = [];
            $row[] = '<label class="checkboxs"><input type="checkbox" class="checked-row" data-value="' . $blog->id . '"><span class="checkmarks"></span></label>';
            $row[] = ++$key;
            $row[] = $blog->title;
            $row[] = $blog->slug;
            $row[] = $thumbnail;
            $row[] = $categories;
            $row[] = $tags;
            $row[] = $author;
            $row[] = '<span class="badge changeBlogType ' . ($blog->blog_type == 1 ? 'badge-linesuccess' : 'badge-linedanger') . '" style="cursor:pointer;" data-blog-id="' . $blog->id . '" data-field="blog_type">' . ($blog->blog_type == 1 ? 'Paid' : 'Free') . '</span>';
            $row[] = '<span class="badge changeStatus ' . ($blog->status == 1 ? 'badge-linesuccess' : 'badge-linedanger') . '" style="cursor:pointer;" data-blog-id="' . $blog->id . '" data-field="status">' . ($blog->status == 1 ? 'Active' : 'Inactive') . '</span>';
            $row[] = '<span class="badge changePublishStatus ' . ($blog->publish_status == 'published' ? 'badge-linesuccess' : 'badge-linedanger') . '" style="cursor:pointer;" data-blog-id="' . $blog->id . '" data-field="publish_status">' . ucfirst($blog->publish_status) . '</span>';
            $row[] = '<div class="action-table-data">
                    <a class="btn btn-info me-2 p-2" href="' . route('blogs.edit', $blog->id) . '">
                        <i class="fa fa-edit text-white"></i>
                    </a>
                    <a class="btn btn-danger delete-btn p-2" href="' . route('blogs.destroy', $blog->id) . '">
                        <i class="fa fa-trash text-white"></i>
                    </a>
                  </div>';
            $data[] = $row;
        }

        $response = [
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalDisplayRecords,
            "aaData"               => $data,
        ];

        return response()->json($response);
    }

    public function create()
    {
        $categories  = Category::all();
        $authors     = Author::all();
        $tags        = Tag::all();
        $enumOptions = Common::getPossibleEnumValues('blogs', 'publish_status');
        return view('backend.modules.blog.create', compact('categories', 'authors', 'tags'), ['enumStatusValues' => $enumOptions]);
    }

    public function generateUniqueSlug($slug)
    {
        // Generate initial slug
        $slug         = $slug;
        $originalSlug = $slug;
        $count        = 1;

        // Check for duplicates and append an incremented number
        while (Blog::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        return $slug;
    }

    public function store(Request $request)
    {
        //dd($request->all());
        // Validate the incoming request data
        $validatedData = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'required',
            'status'           => 'nullable|boolean',
            'content'          => 'required|string',
            'author_id'        => 'nullable|exists:authors,id',
            'publish_status'   => 'nullable|string|in:published,draft',
            'blog_type'        => 'nullable|string|in:0,1',
            'meta_title'       => 'nullable|string|max:255',
            'meta_keywords'    => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'thumbnail'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'meta_image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_ids.*'   => 'string|max:255',
        ]);

        // Generate unique slug
        $slug = $this->generateUniqueSlug($validatedData['slug']);

        // Upload and store the thumbnail image
        $thumbnailPath = $request->hasFile('thumbnail')
        ? uploadImage($request->file('thumbnail'), 'blogs/thumbnails', '0', 80)
        : null;

        // Upload and store the meta image
        $metaImagePath = $request->hasFile('meta_image')
        ? uploadImage($request->file('meta_image'), 'blogs/meta_images', '0', 80)
        : null;

        $blog = new Blog();

        $blog->title = $validatedData['title'];
        $blog->slug  = $slug; // **Use unique slug here**
        // $blog->slug             = $validatedData['slug'];
        $blog->status           = $validatedData['status'] ?? 0;
        $blog->content          = $validatedData['content'];
        $blog->author_id        = $validatedData['author_id'] ?? null;
        $blog->publish_status   = $validatedData['publish_status'] ?? 'draft';
        $blog->blog_type        = $validatedData['blog_type'] ?? '0';
        $blog->meta_title       = $validatedData['meta_title'] ?? null;
        $blog->meta_keywords    = $validatedData['meta_keywords'] ?? null;
        $blog->meta_description = $validatedData['meta_description'] ?? null;
        $blog->thumbnail        = $thumbnailPath;
        $blog->meta_image       = $metaImagePath;

        $blog->save();

        if (! empty($validatedData['category_ids'])) {
            $categoryIds = [];
            foreach ($validatedData['category_ids'] as $categoryNameOrId) {
                if (is_numeric($categoryNameOrId)) {
                    $categoryIds[] = $categoryNameOrId;
                } else {
                    $category = Category::where('name', $categoryNameOrId)->first();

                    if (! $category) {
                        $category = Category::create([
                            'name' => trim($categoryNameOrId),
                            'slug' => Str::slug(trim($categoryNameOrId), '_'),
                        ]);
                    }

                    $categoryIds[] = $category->id;
                }
            }
            $blog->categories()->sync($categoryIds);
        }

        // Sync Tags
        if (! empty($validatedData['tags'])) {
            $tagIds = [];
            foreach ($validatedData['tags'] as $tagNameOrId) {
                if (is_numeric($tagNameOrId)) {
                    $tagIds[] = $tagNameOrId;
                } else {
                    $tag = Tag::where('name', $tagNameOrId)->first();

                    if (! $tag) {
                        $tag = Tag::create([
                            'name' => trim($tagNameOrId),
                            'slug' => Str::slug(trim($tagNameOrId), '_'),
                        ]);
                    }

                    $tagIds[] = $tag->id;
                }
            }
            $blog->tags()->sync($tagIds);
        }

        return response()->json(['message' => 'Blog created successfully!'], 200);
    }

    public function edit($id)
    {
        $blog        = Blog::findOrFail($id);
        $categories  = Category::all();
        $tags        = Tag::all();
        $authors     = Author::all();
        $enumOptions = Common::getPossibleEnumValues('blogs', 'publish_status');

        return view('backend.modules.blog.edit', compact('blog', 'categories', 'tags', 'authors', 'enumOptions'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title'            => 'required|string|max:255',
            // 'slug'             => 'required|unique:blogs,slug,' . $id,
            'slug'             => 'required|string',
            'status'           => 'required|boolean',
            'content'          => 'required|string',
            'category_ids'     => 'nullable|array',
            'category_ids.*'   => 'string|max:255',
            'tags'             => 'nullable|array',
            'tags.*'           => 'string|max:255',
            'author_id'        => 'nullable|exists:authors,id',
            'publish_status'   => 'nullable|string|in:published,draft',
            'blog_type'        => 'nullable|string|in:0,1',
            'meta_title'       => 'nullable|string|max:255',
            'meta_keywords'    => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'thumbnail'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'meta_image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $blog = Blog::findOrFail($id);

        // If the slug has changed, generate a unique one
        if ($validatedData['slug'] !== $blog->slug) {
            $validatedData['slug'] = $this->generateUniqueSlug($validatedData['slug'], $id);
        }

        $previousThumbnailImage = $blog->thumbnail;
        $previousMetaImage      = $blog->meta_image;

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $ThumbnailImagePath = uploadImage($request->file('thumbnail'), 'blogs/thumbnails', '0', 80);
            $blog->thumbnail    = $ThumbnailImagePath;

            if ($previousThumbnailImage) {
                unlink($previousThumbnailImage);
            }

        }

        // Handle meta image upload
        if ($request->hasFile('meta_image')) {
            $MetaImagePath    = uploadImage($request->file('meta_image'), 'blogs/meta_images', '0', 80);
            $blog->meta_image = $MetaImagePath;

            if ($previousMetaImage) {
                unlink($previousMetaImage);
            }
        }

        $blog->title            = $validatedData['title'];
        $blog->slug             = $validatedData['slug'];
        $blog->status           = $validatedData['status'];
        $blog->content          = $validatedData['content'];
        $blog->author_id        = $validatedData['author_id'] ?? null;
        $blog->publish_status   = $validatedData['publish_status'] ?? 'draft';
        $blog->blog_type        = $validatedData['blog_type'] ?? '0';
        $blog->meta_title       = $validatedData['meta_title'] ?? null;
        $blog->meta_keywords    = $validatedData['meta_keywords'] ?? null;
        $blog->meta_description = $validatedData['meta_description'] ?? null;

        $blog->save();

        // Sync Categories
        // if (!empty($validatedData['category_ids'])) {
        //     $categoryIds = [];
        //     foreach ($validatedData['category_ids'] as $categoryNameOrId) {
        //         if (is_numeric($categoryNameOrId)) {
        //             $categoryIds[] = $categoryNameOrId;
        //         } else {
        //             $category = Category::firstOrCreate(['name' => trim($categoryNameOrId)]);
        //             $categoryIds[] = $category->id;
        //         }
        //     }
        //     $blog->categories()->sync($categoryIds);
        // }
        if (! empty($validatedData['category_ids'])) {
            $categoryIds = [];
            foreach ($validatedData['category_ids'] as $categoryNameOrId) {
                if (is_numeric($categoryNameOrId)) {
                    $categoryIds[] = $categoryNameOrId;
                } else {
                    $category = Category::where('name', $categoryNameOrId)->first();

                    if (! $category) {
                        $category = Category::create([
                            'name' => trim($categoryNameOrId),
                            'slug' => Str::slug(trim($categoryNameOrId), '_'),
                        ]);
                    }

                    $categoryIds[] = $category->id;
                }
            }
            $blog->categories()->sync($categoryIds);
        }

        // Sync Tags
        if (! empty($validatedData['tags'])) {
            $tagIds = [];
            foreach ($validatedData['tags'] as $tagNameOrId) {
                if (is_numeric($tagNameOrId)) {
                    $tagIds[] = $tagNameOrId;
                } else {
                    $tag = Tag::where('name', $tagNameOrId)->first();

                    if (! $tag) {
                        $tag = Tag::create([
                            'name' => trim($tagNameOrId),
                            'slug' => Str::slug(trim($tagNameOrId), '_'),
                        ]);
                    }

                    $tagIds[] = $tag->id;
                }
            }

            $blog->tags()->sync($tagIds);
        }

        return response()->json(['message' => 'Blog updated successfully!'], 200);
    }

    //onclick status button update
    public function updateField(Request $request)
    {
        $request->validate([
            'id'    => 'required|integer|exists:blogs,id',
            'field' => 'required|string|in:blog_type,status,publish_status',
        ]);

        $blog = Blog::findOrFail($request->id);

        switch ($request->field) {
            case 'blog_type':
                $blog->blog_type = $blog->blog_type == 1 ? 0 : 1;
                break;
            case 'status':
                $blog->status = $blog->status == 1 ? 0 : 1;
                break;
            case 'publish_status':
                $blog->publish_status = $blog->publish_status == 'published' ? 'draft' : 'published';
                break;
        }

        $blog->save();

        return response()->json(['message' => ucfirst($request->field) . ' updated successfully!'], 200);
    }

    //delete single blog

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);

        $blog->categories()->detach();
        $blog->tags()->detach();

        if ($blog->thumbnail && file_exists(public_path($blog->thumbnail))) {
            unlink(public_path($blog->thumbnail));
        }

        if ($blog->meta_image && file_exists(public_path($blog->meta_image))) {
            unlink(public_path($blog->meta_image));
        }

        $blog->delete();

        return response()->json(['message' => 'Blog deleted successfully.'], 200);
    }

    //mark as delete blog
    public function destroyAll(Request $request)
    {
        $token = base64_decode($request->get("token"));
        $ids   = json_decode($token);

        foreach ($ids as $id) {

            $blog = Blog::find($id);
            if ($blog) {
                $blog->categories()->detach();
                $blog->tags()->detach();

                if ($blog->thumbnail && file_exists(public_path($blog->thumbnail))) {
                    unlink(public_path($blog->thumbnail));
                }

                if ($blog->meta_image && file_exists(public_path($blog->meta_image))) {
                    unlink(public_path($blog->meta_image));
                }

                $blog->delete();
            }
        }

        return response()->json(['message' => 'Successfully deleted selected blogs.'], 200);
    }

}
