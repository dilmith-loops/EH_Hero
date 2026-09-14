<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'ip_address',
    ];

    public function generations(): HasMany
    {
        return $this->hasMany(Generation::class);
    }
}
