<?php

namespace Payment\System\App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    public $fillable = [
        'planId',
        'name',
        'currency',
        'type',
        'price'
    ];
    protected $table = 'plans';

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

}
