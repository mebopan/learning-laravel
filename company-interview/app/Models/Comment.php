<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'user_id',
        'comment',
        'rating',
    ];

    /**
     * user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * company
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
