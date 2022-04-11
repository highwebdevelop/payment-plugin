<?php


namespace Payment\System\App\Services;


use Payment\System\App\Exceptions\PaymentMethodNotFoundException;
use Payment\System\App\Models\PaymentMethod;

class PaymentMethodService
{

    /**
     * @throws PaymentMethodNotFoundException
     */
    public function findByIdOrFail($id)
    {
        $paymentMethod = PaymentMethod::find($id);
        if (!$paymentMethod) throw new PaymentMethodNotFoundException();
        return $paymentMethod;
    }

}
