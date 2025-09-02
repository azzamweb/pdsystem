<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_id', 'component', 'qty', 'unit', 'unit_amount', 'line_total', 
        'ref_table', 'ref_id', 'cap_amount', 'is_over_cap', 'over_cap_amount', 'remark',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
        'unit_amount' => 'decimal:2',
        'line_total' => 'decimal:2',
        'cap_amount' => 'decimal:2',
        'is_over_cap' => 'boolean',
        'over_cap_amount' => 'decimal:2',
    ];

    public function receipt() { return $this->belongsTo(Receipt::class); }
}
