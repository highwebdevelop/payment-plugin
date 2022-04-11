<?php

namespace Payment\System\App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public $fillable = [
        'paymentId',
        'paymentPlatform',
        'status',
        'currency',
        'uuid',
        'paymentPlatform',
        'price',
    ];

    protected $table = 'transactions';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function payment()
    {
        return $this->morphTo();
    }

}
