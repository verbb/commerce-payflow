# Testing a Payflow Payment

Payflow connects your Commerce checkout to your merchant account. Creating the gateway makes it available to Commerce; your storefront still needs a checkout that collects the payment details and submits the payment through that gateway.

## Enter the Merchant Credentials

After creating a Payflow gateway under **Commerce → Settings → Gateways**, enter its **API Username**, **API Password**, **Partner** and **Vendor**. Vendor is the merchant login ID; the API username can differ if the account has additional users. Use the Partner value supplied for that merchant account, including a reseller's value where applicable.

The credential fields support environment-variable suggestions. Use the credentials for the environment you are testing and enable **Test mode?** while qualifying the connection.

## Complete a Test Checkout

Choose the Payflow gateway in your store's checkout and complete a payment using the provider's approved test credentials and payment details. Use a test customer and an order you can identify in both systems.

Open that order in Commerce and inspect its transaction history. Check the payment result and the corresponding merchant-account record, rather than treating the browser's confirmation page alone as proof of payment. If the transaction failed, inspect its response message, then check the account credentials, Partner/Vendor values and test-mode selection before retrying.

Your checkout determines whether it requests a purchase or an authorisation. Confirm that the resulting transaction matches the flow you intend to use, including any later capture or refund operation, before accepting live orders. Do not assume creating the gateway also implements your storefront's checkout templates.

## Use the Live Account

When the test flow is verified, supply the live merchant credentials and disable test mode for the live gateway. Keep the environment values consistent so a live checkout cannot accidentally use test credentials. Review the first real transaction in both Commerce and the merchant account.
