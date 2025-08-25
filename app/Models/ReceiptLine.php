<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_id', 'component', 'qty', 'unit', 'unit_amount', 'line_total', 
        'is_no_lodging', 'destination_city', 'remark',
    ];

    protected $casts = [
        'is_no_lodging' => 'boolean',
    ];

    public function receipt() { return $this->belongsTo(Receipt::class); }
}
