<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'first_name',
        'last_name',
        'username',
        'referred_by',
        'enrolled_date',
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
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'purchaser_id');
    }

    /**
     * Get the distributors referred by the user.
     */
    public function referredDistributors()
    {
        return $this->referredUsers()->whereHas('categories', function ($query) {
            $query->where('name', 'Distributor');
        });
    }

    /**
     * Get the category of the user.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'user_category', 'user_id', 'category_id');
    }

    /**
     * Get the referrer of the user.
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }
     // Relationship to get the users referred by this user
     public function referredUsers()
     {
         return $this->hasMany(User::class, 'referred_by');
     }

    /**
     * Get the full name of the user.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
