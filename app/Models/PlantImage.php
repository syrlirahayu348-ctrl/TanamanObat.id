<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantImage extends Model
{
    use HasFactory;

    protected $fillable = ['plant_id', 'image_path', 'caption', 'is_primary'];

    protected $casts = ['is_primary' => 'boolean'];

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }
}
