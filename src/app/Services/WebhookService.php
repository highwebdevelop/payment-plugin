<?php


namespace Payment\System\App\Services;


class WebhookService
{
    const SUBSCRIPTION_CREATED = 'subscription.created';
    const SUBSCRIPTION_CHARGED_SUCCESSFULLY = 'subscription.chargedSuccessfully';
    const SUBSCRIPTION_CHARGE_FAILED = 'subscription.chargeFailed';

    const INVOICE_CHARGED_SUCCESSFULLY = 'invoice.approved';
    const INVOICE_CHARGE_FAILED = 'invoice.failed';

    const TRANSACTION_CREATED = 'transaction.created';
    const TRANSACTION_CHARGED_SUCCESSFULLY = 'transaction.chargeFailed';
    const TRANSACTION_CHARGE_FAILED = 'transaction.chargeFailed';
}
