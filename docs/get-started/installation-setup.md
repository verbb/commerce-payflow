# Installation & Setup
You can install PayPal Payflow via the plugin store, or through Composer.

## Craft Plugin Store
To install **PayPal Payflow**, navigate to the _Plugin Store_ section of your Craft control panel, search for `PayPal Payflow`, and click the _Try_ button.

## Composer
You can also add the package to your project using Composer and the command line.

1. Open your terminal and go to your Craft project:
```shell
cd /path/to/project
```

2. Then tell Composer to require the plugin, and Craft to install it:
```shell
composer require verbb/commerce-payflow && php craft plugin/install commerce-payflow
```

## Setup
To add the payment gateway, go to **Commerce** → **Settings** → **Gateways**, create a new gateway, and set the gateway type to `Payflow`.
