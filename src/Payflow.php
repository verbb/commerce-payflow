<?php
namespace verbb\payflow;

use verbb\payflow\gateways\Payflow as PayflowGateway;

use Craft;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;

use craft\commerce\services\Gateways;

use yii\base\Event;

class Payflow extends Plugin
{
    // Public Properties
    // =========================================================================

    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();

        Event::on(Gateways::class, Gateways::EVENT_REGISTER_GATEWAY_TYPES, function(RegisterComponentTypesEvent $event) {
            $event->types[] = PayflowGateway::class;
        });
    }
}
