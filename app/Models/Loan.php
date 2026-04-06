<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'status',
        'borrow_date',
        'expected_return_date',
        'return_photo',
        'returned_at'
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'expected_return_date' => 'date',
        'returned_at' => 'datetime',
        'approved_at' => 'datetime',
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
