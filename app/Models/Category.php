<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'icon', 'color'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function plants()
    {
        return $this->hasMany(Plant::class);
    }

    public function publishedPlants()
    {
        return $this->hasMany(Plant::class)->where('status', 'published');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
