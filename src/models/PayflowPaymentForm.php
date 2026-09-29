<?php
namespace verbb\payflow\models;

use craft\commerce\models\payments\CreditCardPaymentForm;
use craft\commerce\models\PaymentSource;

class PayflowPaymentForm extends CreditCardPaymentForm
{
    // Properties
    // =========================================================================

    /**
     * Stored outside the model attributes so request mass assignment cannot provide payment authority.
     */
    private ?string $_cardReference = null;


    // Public Methods
    // =========================================================================

    public function getCardReference(): ?string
    {
        return $this->_cardReference;
    }

    public function populateFromPaymentSource(PaymentSource $paymentSource): void
    {
        $this->_cardReference = $paymentSource->token;
    }


    // Protected Methods
    // =========================================================================

    protected function defineRules(): array
    {
        if (empty($this->_cardReference)) {
            return parent::defineRules();
        }

        return [];
    }
}
