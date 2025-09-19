<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id', 'title', 'slug', 'description', 'video_url',
        'video_type', 'thumbnail', 'duration_seconds', 'order', 'is_published'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'duration_seconds' => 'integer',
        'order' => 'integer',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}