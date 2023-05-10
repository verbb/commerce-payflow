<?php
namespace verbb\payflow;

use verbb\payflow\gateways\Payflow as PayflowGateway;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;

use craft\commerce\Plugin as Commerce;
use craft\commerce\services\Gateways;
use craft\commerce\omnipay\events\SendPaymentRequestEvent;
use craft\commerce\omnipay\base\Gateway as Gateway;

use yii\base\Event;

class Payflow extends Plugin
{
    // Properties
    // =========================================================================

    public string $schemaVersion = '1.0.0';

    
    // Public Methods
    // =========================================================================

    public function init(): void
    {
        parent::init();

        Event::on(Gateways::class, Gateways::EVENT_REGISTER_GATEWAY_TYPES, function(RegisterComponentTypesEvent $event) {
            $event->types[] = PayflowGateway::class;
        });

        // When using a saved card, or creating one, we must add the currency to the request - will receive 
        // `Invalid or unsupported currency code` otherwise
        Event::on(Gateway::class, Gateway::EVENT_BEFORE_SEND_PAYMENT_REQUEST, function(SendPaymentRequestEvent $event) {
            $event->modifiedRequestData = $event->requestData;
            $event->modifiedRequestData['CURRENCY'] = Commerce::getInstance()->getPaymentCurrencies()->getPrimaryPaymentCurrencyIso();
        });
    }
}
