<?php

namespace Payment\System\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'is_active'
    ];
}