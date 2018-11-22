<?php
namespace verbb\payflow\gateways;

use verbb\payflow\models\PayflowPaymentForm;

use Craft;
use craft\helpers\StringHelper;
use craft\commerce\omnipay\base\CreditCardGateway;
use craft\commerce\models\payments\BasePaymentForm;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\ResponseInterface;
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

    public $sendCartInfo = true;


    // Public Methods
    // =========================================================================

    public static function displayName(): string
    {
        return Craft::t('commerce', 'PayPal Payflow');
    }

    public function getPaymentFormModel(): BasePaymentForm
    {
        return new PayflowPaymentForm();
    }

    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('commerce-payflow/gatewaySettings', ['gateway' => $this]);
    }

    public function populateRequest(array &$request, BasePaymentForm $paymentForm = null)
    {
        parent::populateRequest($request, $paymentForm);

        // Temp Fix for "Invalid or unsupported currency code" - investigate more!
        $request['currency'] = 'AUD';

        if ($paymentForm && $paymentForm->hasProperty('cardReference') && $paymentForm->cardReference) {
            $request['cardReference'] = $paymentForm->cardReference;
        }
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

    protected function extractPaymentSourceDescription(ResponseInterface $response): string
    {
        $data = $response->getData();

        // Payflow's response doesn't give us information about the card or type. Get it from the request instead
        $card = $response->getRequest()->getCard();
        $type = $card->getBrand();
        $number = $card->getNumberLastFour();

        return Craft::t('commerce-payflow', '{cardType} ending in {last4}', ['cardType' => StringHelper::upperCaseFirst($type), 'last4' => $number]);
    }

    protected function getGatewayClassName()
    {
        return '\\' . ProGateway::class;
    }
}
