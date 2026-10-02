<?php
namespace verbb\payflow;

use verbb\payflow\gateways\Payflow as PayflowGateway;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;

use craft\commerce\Plugin as Commerce;
use craft\commerce\omnipay\events\SendPaymentRequestEvent;
use craft\commerce\services\Gateways;

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

        // Payflow card-creation requests omit their currency, but transaction requests already contain the
        // currency paired with their amount and must remain unchanged.
        Event::on(PayflowGateway::class, PayflowGateway::EVENT_BEFORE_SEND_PAYMENT_REQUEST, function(SendPaymentRequestEvent $event) {
            if (
                !is_array($event->requestData) ||
                ($event->requestData['TRXTYPE'] ?? null) !== 'L' ||
                array_key_exists('CURRENCY', $event->requestData)
            ) {
                return;
            }

            $event->modifiedRequestData = $event->requestData;
            $event->modifiedRequestData['CURRENCY'] = Commerce::getInstance()->getPaymentCurrencies()->getPrimaryPaymentCurrencyIso();
        });
    }
}
