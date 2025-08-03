<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price',
        'product_attributes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'product_attributes' => 'array',
    ];

    /**
     * Get the cart that owns the cart item.
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get the product that owns the cart item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the total for this cart item.
     */
    public function getTotalAttribute(): float
    {
        return $this->price * $this->quantity;
    }

    /**
     * Increase the quantity of this cart item.
     */
    public function increaseQuantity(int $amount = 1): void
    {
        $this->increment('quantity', $amount);
    }

    /**
     * Decrease the quantity of this cart item.
     */
    public function decreaseQuantity(int $amount = 1): void
    {
        $newQuantity = $this->quantity - $amount;
        if ($newQuantity <= 0) {
            $this->delete();
        } else {
            $this->decrement('quantity', $amount);
        }
    }
}
