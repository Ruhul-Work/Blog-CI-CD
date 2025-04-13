<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'blogs';

    // Fields that can be mass-assigned
    protected $fillable = [
        'title',
        'slug',
        'content',
        'short_description',
        'author_id',
        'user_id',
        'blog_type',
        'price',
        'total_views',
        'likes_count',
        'comments_count',
        'share_counts',
        'publish_status',
        'published_at',
        'read_count',
        'allow_comments',
        'thumbnail',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_image',
        'status',
        'is_featured',
        'printable',
        'liked_by',
    ];

    // Automatically cast these attributes
    protected $casts = [
        'blog_type'            => 'boolean',
        'allow_comments'       => 'boolean',
        'is_featured'          => 'boolean',
        'printable'            => 'boolean',
        'status'               => 'boolean',
        'price'                => 'float',
        'total_views'          => 'integer',
        'likes_count'          => 'integer',
        'comments_count'       => 'integer',
        'read_time'            => 'integer',
        'published_at'         => 'datetime',
        'scheduled_publish_at' => 'datetime',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'published_at', 'scheduled_publish_at'];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'blog_id');
    }

    // Define the many-to-many relationship with Category
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'blog_categories', 'blog_id', 'category_id');
    }

    // Define the many-to-many relationship with Tag
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_tags', 'blog_id', 'tags_id');
    }

    
    //ShareCount
    public function incrementShareCount()
    {
        $this->increment('share_counts');
    }

    //liked user
    public function isLikedByUser($userId)
    {
        $likedBy = $this->liked_by ? json_decode($this->liked_by, true) : [];
        return in_array($userId, $likedBy);
    }

    //total view

    public function incrementTotalViews()
    {
        $this->increment('total_views');
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('publish_status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Boot method to handle created_by, updated_by, and deleted_by fields
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically assign user on create/update/delete
        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        static::deleting(function ($model) {
            $model->deleted_by = Auth::id();
            $model->save();
        });
    }

    /**
     * Find a blog post by slug
     */
    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }

}
