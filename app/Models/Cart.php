<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
    ];

    /**
     * Get the user that owns the cart.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the cart items for the cart.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the total quantity of items in the cart.
     */
    public function getTotalQuantityAttribute(): int
    {
        return $this->cartItems->sum('quantity');
    }

    /**
     * Get the total amount of the cart.
     */
    public function getTotalAmountAttribute(): float
    {
        return $this->cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    /**
     * Check if cart is empty.
     */
    public function isEmpty(): bool
    {
        return $this->cartItems->count() === 0;
    }

    /**
     * Clear all items from the cart.
     */
    public function clear(): void
    {
        $this->cartItems()->delete();
    }
}
