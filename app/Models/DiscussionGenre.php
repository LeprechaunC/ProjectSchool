<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DiscussionGenre extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    public function discussions(): HasMany
    {
        return $this->hasMany(Discussion::class, 'genre_id');
    }
} 