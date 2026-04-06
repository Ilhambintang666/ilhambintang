<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;
    
   protected $fillable = [
    'borrower_name',
    'item_id',
    'borrow_date',
    'expected_return_date',
    'notes',
    'status',
];
    
    protected $casts = [
        'borrow_date' => 'date',
        'return_date' => 'date',
        'expected_return_date' => 'date'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}