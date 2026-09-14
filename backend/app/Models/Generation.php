<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Generation extends Model
{
    use HasFactory;

    protected $fillable = [
        'app_user_id',
        'treat_id',
        'treat_name',
        'style_id',
        'custom_prompt',
        'original_image_path',
        'generated_image_path',
        'ip_address',
    ];

    protected $appends = [
        'original_image_url',
        'generated_image_url',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(AppUser::class, 'app_user_id');
    }

    public function getOriginalImageUrlAttribute(): ?string
    {
        return $this->original_image_path ? Storage::url($this->original_image_path) : null;
    }

    public function getGeneratedImageUrlAttribute(): string
    {
        return Storage::url($this->generated_image_path);
    }
}
