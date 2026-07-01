<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Plant extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'user_id', 'local_name', 'latin_name', 'slug',
        'description', 'benefits', 'usage', 'side_effects', 'image',
        'status', 'views', 'origin', 'harvest_season',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($plant) {
            if (empty($plant->slug)) {
                $plant->slug = Str::slug($plant->local_name . '-' . time());
            }
        });
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopePopular($query, $limit = 6)
    {
        return $query->orderByDesc('views')->limit($limit);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('local_name', 'like', "%{$term}%")
              ->orWhere('latin_name', 'like', "%{$term}%")
              ->orWhere('benefits', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%");
        });
    }

    // Relations
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(PlantImage::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    // Helpers
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }
        return asset('images/plant-placeholder.jpg');
    }

    public function incrementViews()
    {
        $this->increment('views');
    }

    public function isFavoritedBy(?User $user): bool
    {
        if (!$user) return false;
        return $this->favorites()->where('user_id', $user->id)->exists();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->latest();
    }

    public function averageRating()
    {
        return $this->hasMany(Comment::class)->whereNotNull('rating')->avg('rating') ?: 0;
    }
}
