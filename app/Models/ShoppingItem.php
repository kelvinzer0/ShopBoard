<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ShoppingItem extends Model
{
    protected $fillable = [
        'title',
        'problem',
        'benefit',
        'budget',
        'purchase_link',
        'status',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
    ];

    protected function formattedBudget(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Rp ' . number_format((float) $this->budget, 0, ',', '.'),
        );
    }
}
