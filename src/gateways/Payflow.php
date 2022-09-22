<?php
namespace verbb\payflow\gateways;

use verbb\payflow\models\PayflowPaymentForm;

use Craft;
use craft\helpers\StringHelper;
use craft\commerce\omnipay\base\CreditCardGateway;
use craft\commerce\models\payments\BasePaymentForm;
use craft\commerce\models\PaymentSource;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Omnipay;
use Omnipay\PayFlow\ProGateway;

class Payflow extends CreditCardGateway
{
    // Properties
    // =========================================================================

    public ?string $username = null;
    public ?string $password = null;
    public ?string $partner = null;
    public ?string $vendor = null;
    public ?string $testMode = null;

    public bool $sendCartInfo = true;


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

    public function getSettingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate('commerce-payflow/gatewaySettings', ['gateway' => $this]);
    }

    public function populateRequest(array &$request, BasePaymentForm $paymentForm = null)
    {
        parent::populateRequest($request, $paymentForm);

        // Temp Fix for "Invalid or unsupported currency code" - investigate more!
        if ($this->testMode) {
            $request['currency'] = null;
        }

        if ($paymentForm && $paymentForm->hasProperty('cardReference') && $paymentForm->cardReference) {
            $request['cardReference'] = $paymentForm->cardReference;
        }
    }

    public function createPaymentSource(BasePaymentForm $sourceData, int $userId): PaymentSource
    {
        try {
            // Without a try/catch, we'll get an internal server error for a failed card.
            // At least in Commerce 2.2.18.
            return parent::createPaymentSource($sourceData, $userId);
        } catch (\Throwable $e) {
            // Have to return something here
            return new PaymentSource();
        }
    }


    // Protected Methods
    // =========================================================================

    protected function createGateway(): AbstractGateway
    {
        $gateway = static::createOmnipayGateway('Payflow_Pro');

        $gateway->setUsername(Craft::parseEnv($this->username));
        $gateway->setPassword(Craft::parseEnv($this->password));
        $gateway->setPartner(Craft::parseEnv($this->partner));
        $gateway->setVendor(Craft::parseEnv($this->vendor));
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
        return '\‘ . ProGateway::class;
    }
}
