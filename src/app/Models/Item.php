<?php

namespace Payment\System\App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'invoice_id',
        'name',
        'price',
        'description',
    ];
    protected $table = 'invoiceItems';
    public $timestamps = false;
}
