<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * Company
 */
class Company extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
        'phone',
        'principal',
    ];

    /**
     * comments
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * scopeWithAvgRating
     */
    public function scopeWithAvgRating(Builder $query) : Builder
    {
        return $query->withAvg('comments', 'rating');
    }

    /**
     * scopeLatestComment
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithLatestComment(Builder $query) : Builder
    {
        return $query->withMax('comments', 'created_at');
    }

    /**
     * scopeName
     *
     * @param Builder $query
     * @param string $name
     * @return Builder
     */
    public function scopeName(Builder $query, string $name): Builder
    {
        return $query->where('name', 'LIKE', '%' . $name . '%');
    }
}
