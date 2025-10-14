<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDiscountLimit extends Model
{
    protected $fillable = [
        'user_id',
        'max_discount_percent',
        'max_discount_amount',
        'is_active',
    ];

    protected $casts = [
        'max_discount_percent' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if user can apply discount
     */
    public static function canApplyDiscount($userId, $discountPercent, $discountAmount = null)
    {
        $limit = self::where('user_id', $userId)
            ->where('is_active', true)
            ->first();

        if (!$limit) {
            return false;
        }

        if ($discountPercent > $limit->max_discount_percent) {
            return false;
        }

        if ($limit->max_discount_amount && $discountAmount > $limit->max_discount_amount) {
            return false;
        }

        return true;
    }

    /**
     * Get user's max discount
     */
    public static function getUserMaxDiscount($userId)
    {
        $limit = self::where('user_id', $userId)
            ->where('is_active', true)
            ->first();

        return $limit ? $limit->max_discount_percent : 0;
    }
}

