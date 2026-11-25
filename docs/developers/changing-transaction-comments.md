# Changing Transaction Comments

Each Payflow transaction has two comment fields, Comment1 and Comment2, which PayPal Manager shows in the transaction’s details. Commerce fills Comment1 with `Order #` and the order’s ID, such as `Order #1234`, and leaves Comment2 empty. If your team matches Payflow transactions to orders by order number or customer email, you can change both comments from a module or plugin.

## Setting the Comments
Commerce Omnipay triggers its `buildGatewayRequest` event while it prepares each request for Payflow. Listen for it on the Payflow gateway class, then set `description` in the request for Comment1 and `comment2` for Comment2. Register the listener in your module or plugin’s `init()` method.

```php
use craft\commerce\omnipay\events\BuildGatewayRequestEvent;
use verbb\payflow\gateways\Payflow;
use yii\base\Event;

Event::on(Payflow::class, Payflow::EVENT_BUILD_GATEWAY_REQUEST, function(BuildGatewayRequestEvent $event) {
    $order = $event->transaction->getOrder();

    $event->request['description'] = 'Order ' . $order->getShortNumber();
    $event->request['comment2'] = $order->email;
});
```

A payment for an order with the number `1a2b3c4d…` now shows `Order 1a2b3c4` as Comment1 and the customer’s email as Comment2. Because the listener is attached to the Payflow gateway class, requests to any other Omnipay gateway on the site keep their usual values.

The event is triggered for purchases, authorizations, captures and refunds, so the same comments follow the order through each of its transactions. Check `$event->type` against the transaction types in `craft\commerce\records\Transaction` if you only want to change some of them.

The `buildGatewayRequest` event requires Commerce Omnipay 4.2.0 or later. Run `composer show craftcms/commerce-omnipay` in your project root to see the installed version, and `composer update craftcms/commerce-omnipay` to update it.

## Using the Order Reference
The order reference can’t be used in a comment for a payment. Commerce generates the reference when it marks the order complete, which happens after the payment succeeds, so `$order->reference` is still empty when the payment request is built. Use the order number or short number instead, since every cart has these before it’s paid.
