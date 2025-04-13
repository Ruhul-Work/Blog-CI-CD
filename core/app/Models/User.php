<?php
namespace App\Models;

use App\Models\Role;
use App\Models\SubscriptionOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_otp_send'     => 'datetime',
    ];

    public function package()
    {
        return $this->belongsTo(SubscriptionOrder::class, 'user_id');
    }

    public function packageshow()
    {
        return $this->hasOne(SubscriptionOrder::class, 'user_id')->where('payment_status', 'paid');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'user_role', 'id')->withDefault();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function points()
    {
        return $this->hasMany(Point::class, 'user_id');
    }

    //check user get active subscription or not
    public function hasActiveSubscription()
    {
        return $this->hasOne(SubscriptionOrder::class, 'user_id')
            ->where('payment_status', 'paid')
            ->where('subscription_start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->exists();
    }

   // Get latest subscription

    public function latestSubscription()
    {
        return $this->hasOne(SubscriptionOrder::class, 'user_id')
            ->latest('subscription_start_date'); // Get latest subscription (active or inactive)
    }

}
