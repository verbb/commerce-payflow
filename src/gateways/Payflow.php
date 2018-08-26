<?php
namespace verbb\payflow\gateways;

use Craft;
use craft\commerce\omnipay\base\CreditCardGateway;

use Omnipay\Common\AbstractGateway;
use Omnipay\Omnipay;
use Omnipay\PayFlow\ProGateway;

class Payflow extends CreditCardGateway
{
    // Properties
    // =========================================================================

    public $username;
    public $password;
    public $partner;
    public $vendor;
    public $testMode;


    // Public Methods
    // =========================================================================

    public static function displayName(): string
    {
        return Craft::t('commerce', 'PayPal Payflow');
    }

    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('commerce-payflow/gatewaySettings', ['gateway' => $this]);
    }


    // Protected Methods
    // =========================================================================

    protected function createGateway(): AbstractGateway
    {
        $gateway = Omnipay::create('Payflow_Pro');

        $gateway->setUsername($this->username);
        $gateway->setPassword($this->password);
        $gateway->setPartner($this->partner);
        $gateway->setVendor($this->vendor);
        $gateway->setTestMode($this->testMode);

        return $gateway;
    }

    protected function getGatewayClassName()
    {
        return '\\' . ProGateway::class;
    }
}
