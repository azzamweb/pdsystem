<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_id', 'component', 'qty', 'unit', 'unit_amount', 'line_total', 'ref_table', 'ref_id',
        'cap_amount', 'is_over_cap', 'over_cap_amount', 'remark',
    ];

    public function receipt() { return $this->belongsTo(Receipt::class); }
}
