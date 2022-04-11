<?php

namespace Payment\System\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Payment\System\App\Models\Concerns\UsesUuid;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'operator',
        'method',
        'logo',
        'is_active',
        'is_recurrent'
    ];


}
