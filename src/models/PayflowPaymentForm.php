<?php
namespace verbb\payflow\models;

use craft\commerce\models\payments\CreditCardPaymentForm;
use craft\commerce\models\PaymentSource;

class PayflowPaymentForm extends CreditCardPaymentForm
{
    // Properties
    // =========================================================================

    public mixed $cardReference = null;


    // Public Methods
    // =========================================================================

    public function populateFromPaymentSource(PaymentSource $paymentSource) : void
    {
        $this->cardReference = $paymentSource->token;
    }


    // Protected Methods
    // =========================================================================

    protected function defineRules(): array
    {
        if (empty($this->cardReference)) {
            return parent::defineRules();
        }

        return [];
    }
}
