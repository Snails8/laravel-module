<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * 実装例
 */
class Work extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function workTags(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\WorkTags')->withTimestamps();
    }
}
