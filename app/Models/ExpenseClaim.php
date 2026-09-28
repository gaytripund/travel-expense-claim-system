<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseClaim extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'travel_from',
        'travel_to',
        'travel_date',
        'amount',
        'description',
        'bill_path',
        'status',
        'manager_comment',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}