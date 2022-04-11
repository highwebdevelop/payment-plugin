<?php

namespace Payment\System\App\Http\Traits;

use Carbon\Carbon;
use Payment\System\App\Models\Invoice;
use Payment\System\App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait UserTrait
{
    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class)
            ->where('status', 'active')
            ->whereDate('expires_at', '>=', Carbon::now());
    }


}