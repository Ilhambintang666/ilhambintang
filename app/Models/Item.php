<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'barcode',
        'description',
        'category_id',
        'location_id',
        'condition',
        'status',
        'is_loanable',
        'purchase_date',
        'price'
    ];
    
    protected $casts = [
        'purchase_date' => 'date',
        'price' => 'decimal:2',
        'is_loanable' => 'boolean',
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
}
