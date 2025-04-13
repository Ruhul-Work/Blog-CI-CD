<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class Product extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'english_name',
        'bangla_name',
        'searchable_data',
        'slug',
        'status',
        'isBundle',
        'product_type',
        'publisher_id',
        'published_year',
        'edition',
        'pages_no',
        'cover_type',
        'weight',
        'isbn',
        'product_code',
        'short_description',
        'description',

        'purchase_price',
        'mrp_price',
        'current_price',
        'discount_type',
        'discount_amount',
        'show_discount',
        'stock',
        'stock_status',

        'thumb_image',
        'meta_title',
        'meta_description',
        'meta_image',
        'created_by',
        'updated_by',
        'deleted_by',
    ];


    protected static function boot()
    {
        parent::boot();

        // Creating event
        static::creating(function ($product) {
            $product->product_code = self::generateProductCode();
            $product->searchable_data = $product->getSearchableData();
            $product->created_by = Auth::id();


        });

        // Updating event
        static::updating(function ($product) {
            $product->searchable_data = $product->getSearchableData();
            $product->updated_by = Auth::id();
        });

        // Deleting event
        static::deleting(function ($product) {
            $product->deleted_by = Auth::id();
            $product->save();
        });
    }


    protected static function generateProductCode()
    {
        // // Loop until a unique SKU is generated
        // do {
        //     // Generate a random number with 4 digits
        //     $uniqueId = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);

        //     $potentialCode = 'EM' . $uniqueId;
        //     // Check if a product with this code already exists
        //     $existingProduct = static::where('product_code', $potentialCode)->first();
        // } while ($existingProduct);

        // return $potentialCode;




         $lastOrderId = static::withTrashed()->max('id');
                 $newProductId = $lastOrderId + 1;
                 $uniqueId = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);
                 $productCode = 'EM' . $newProductId. '_' . $uniqueId;
                 return $productCode;
    }


    private function getSearchableData()
    {

        $searchableFields = [
            'author_name' => optional($this->authors)->pluck('name')->implode(', '),
            'publisher_name' => optional($this->publisher)->name,
            'category_name' => optional($this->categories)->pluck('name')->implode(', '),
            'subcategory_name' => optional($this->subcategories)->pluck('name')->implode(', '),
        ];


        return implode(' ,', array_filter($searchableFields, function ($value) {
            return $value !== null;
        }));
    }



//newly added


    public static function bestSeller($limit = 10)
    {
        return self::select('products.*')
            ->join('orders_items', 'products.id', '=', 'orders_items.product_id')
            ->selectRaw('products.*, SUM(orders_items.qty) as total_sold')
            ->groupBy('products.id')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }


    public function scopeApplySorting($query, $sortBy)
    {
        switch ($sortBy) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'price_asc':
                $query->orderBy('current_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('current_price', 'desc');
                break;
            case 'discount_asc':
                $query->orderBy('discount_amount', 'asc');
                break;
            case 'discount_desc':
                $query->orderBy('discount_amount', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('bangla_name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('bangla_name', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query;
    }



    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }


    public function hasActiveCampaign()
    {
        return $this->campaigns()->where('status',1)->where('end_date', '>=', now())->exists();
    }

    public function getActiveCampaign()
    {
        return $this->campaigns()->where('status',1)->where('end_date', '>=', now())->first();
    }



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function subcategories()
    {
        return $this->belongsToMany(Subcategory::class);
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function bundleProducts()
    {
        return $this->hasMany(BundleProduct::class, 'product_id', 'id');
    }


    public function variants()
    {
        return $this->belongsToMany(Variant::class)->withPivot('price', 'stock', 'description');
    }


    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class ,'campaign_products');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }







}
