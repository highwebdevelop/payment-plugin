<?php

namespace Payment\System\App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'user_id',
        'uuid',
        'status',
        'paymentMethod',
        'paymentType',
        'approvalLink',
        'description',
        'expires_at',
        'plan_id'
    ];
    protected $table = 'invoices';

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'payment');
    }

    /**\
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
