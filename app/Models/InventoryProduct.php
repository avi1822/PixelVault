<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryProduct extends Model
{
    use HasFactory;

    protected $table = 'inventory_products';

    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'cost_price',
        'selling_price',
        'stock_quantity',
        'reorder_level',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(InventoryCategory::class, 'category_id');
    }

    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class, 'product_id');
    }
}
