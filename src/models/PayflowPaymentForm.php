<?php
namespace verbb\payflow\models;

use craft\commerce\models\payments\CreditCardPaymentForm;
use craft\commerce\models\PaymentSource;

class PayflowPaymentForm extends CreditCardPaymentForm
{
    public mixed $cardReference = null;

    public function populateFromPaymentSource(PaymentSource $paymentSource) : void
    {
        $this->cardReference = $paymentSource->token;
    }

    public function rules(): array
    {
        if (empty($this->cardReference)) {
            return parent::rules();
        }

        return [];
    }
}
