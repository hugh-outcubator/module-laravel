# About Paymentwall
[Paymentwall](http://paymentwall.com/?source=gh) is the leading digital payments platform for globally monetizing digital goods and services. Paymentwall assists game publishers, dating sites, rewards sites, SaaS companies and many other verticals to monetize their digital content and services. 
Merchants can plugin Paymentwall's API to accept payments from over 100 different methods including credit cards, debit cards, bank transfers, SMS/Mobile payments, prepaid cards, eWallets, landline payments and others. 

To sign up for a Paymentwall Merchant Account, [click here](http://paymentwall.com/signup/merchant?source=gh).

# Paymentwall PHP Library
This library allows developers to use [Paymentwall APIs](http://paymentwall.com/en/documentation/API-Documentation/722?source=gh) (Virtual Currency, Digital Goods featuring recurring billing, and Virtual Cart).

To use Paymentwall, all you need to do is to sign up for a Paymentwall Merchant Account so you can setup an Application designed for your site.
To open your merchant account and set up an application, you can [sign up here](http://paymentwall.com/signup/merchant?source=gh).

# Installation
To install the library in your environment, you have several options:

1. Download ZIP Archive:
   - Download the [ZIP archive](https://github.com/paymentwall/paymentwall-php/archive/master.zip).
   - Unzip it.
   - Place it into your project

2. Git Clone:
   - Use the following command to clone the repository: 
   <code>git clone git://github.com/paymentwall/module-laravel.git</code>

3. Composer: 
   - <code>composer require paymentwall/module-laravel</code>
4. Publish config
   - <code>php artisan vendor:publish --tag=paymentwall-config</code>

Then use a code sample below.

# Code Samples 

## Digital Goods API

#### Initializing Paymentwall
Using Paymentwall PHP Library:

```php
//Update paymentwall configs in config/paymentwall.php

'public_key' => 'your_public_key',
'private_key' => 'your_private_key',
```

```php
use PaymentwallLaravel\Lib\Config;

Config::getInstance()->set([
    'api_type' => Config::API_GOODS
]);
```

#### Widget Call
[Web API details](http://www.paymentwall.com/en/documentation/Digital-Goods-API/710#paymentwall_widget_call_flexible_widget_call)

The widget is a payment page hosted by Paymentwall that embeds the entire payment flow: selecting the payment method, completing the billing details, and providing customer support via the Help section. You can redirect the users to this page or embed it via iframe. Below is an example that renders an iframe with Paymentwall Widget.

```php
use PaymentwallLaravel\Lib\Widget;
use PaymentwallLaravel\Lib\Product;

$widget = new Widget(
	'user40012',   // id of the end-user who's making the payment
	'pw',          // widget code, e.g. pw; can be picked inside of your merchant account
	[         // product details for Flexible Widget Call. To let users select the product on Paymentwall's end, leave this array empty
		new Product(
			'product301',                           // id of the product in your system
			9.99,                                   // price
			'USD',                                  // currency code
			'Gold Membership',                      // product name
			Product::TYPE_SUBSCRIPTION, // this is a time-based product; for one-time products, use Product::TYPE_FIXED and omit the following 3 array elements
			1,                                      // duration is 1
			Product::PERIOD_TYPE_MONTH, //               month
			true                                    // recurring
		)
  	],
	['email' => 'user@hostname.com']           // additional parameters
);
echo $widget->getHtmlCode();
```

#### Pingback Processing

The Pingback is a webhook notifying about a payment being made. Pingbacks are sent via HTTP/HTTPS to your servers. To process pingbacks use the following code:
```php
use PaymentwallLaravel\Lib\Config;

Config::getInstance()->set([
    'api_type' => Config::API_GOODS
]);

$pingback = new Pingback($_GET, $_SERVER['REMOTE_ADDR']);
if ($pingback->validate(true)) {
  $productId = $pingback->getProduct()->getId();
  if ($pingback->isDeliverable()) {
  // deliver the product
  } else if ($pingback->isCancelable()) {
  // withdraw the product
  } else if ($pingback->isUnderReview()) {
  // set "pending" status to order  
  }
  echo 'OK'; // Paymentwall expects response to be OK, otherwise the pingback will be resent
} else {
  echo $pingback->getErrorSummary();
}
```

## Virtual Currency API

#### Initializing Paymentwall
Using Paymentwall PHP Library:
```php
use PaymentwallLaravel\Lib\Config;

Config::getInstance()->set([
    'api_type' => Config::API_VC
]);
```

#### Widget Call
```php
use PaymentwallLaravel\Lib\Widget;

$widget = new Widget(
	'user40012', // id of the end-user who's making the payment
	'p1_1',      // widget code, e.g. p1; can be picked inside of your merchant account
	[],     // array of products - leave blank for Virtual Currency API
	['email' => 'user@hostname.com'] // additional parameters
);
echo $widget->getHtmlCode();
```

#### Pingback Processing

```php
use PaymentwallLaravel\Lib\Config;

Config::getInstance()->set([
    'api_type' => Config::API_VC
]);

$pingback = new Pingback($_GET, $_SERVER['REMOTE_ADDR']);
if ($pingback->validate(true)) {
  $virtualCurrency = $pingback->getVirtualCurrencyAmount();
  if ($pingback->isDeliverable()) {
  // deliver the virtual currency
  } else if ($pingback->isCancelable()) {
  // withdraw the virtual currency
  } else if ($pingback->isUnderReview()) {
  // set "pending" status to order
  }
  echo 'OK'; // Paymentwall expects response to be OK, otherwise the pingback will be resent
} else {
  echo $pingback->getErrorSummary();
}
```

## Cart API

#### Initializing Paymentwall
Using Paymentwall PHP Library:
```php
use PaymentwallLaravel\Lib\Config;

Config::getInstance()->set([
    'api_type' => Config::API_CART
]);
```

#### Widget Call
Stored products call example (when products are stored in Paymentwall):  
```php
use PaymentwallLaravel\Lib\Widget;
use PaymentwallLaravel\Lib\Product;

$widget = new Widget(
	'user40012', // id of the end-user who's making the payment
	'p1_1',      // widget code, e.g. p1; can be picked inside of your merchant account,
	[
		new Product('product301', 3.33, 'EUR'), // first product in cart
		new Product('product607', 7.77, 'EUR')  // second product in cart
	],
	['email' => 'user@hostname.com'] // additional params
);
echo $widget->getHtmlCode();
```

Non-stored products call example (when products are not stored in Paymentwall):

```php
use PaymentwallLaravel\Lib\Widget;
use PaymentwallLaravel\Lib\Product;

$widget = new Widget(
	'user40012', // id of the end-user who's making the payment
	'p1_1',      // widget code, e.g. p1; can be picked inside of your merchant account,
	[
		new Product('product301', 3.33, 'EUR', 'Product 1'), // first product in cart
		new Product('product607', 7.77, 'EUR', 'Product 2')  // second product in cart
	],
	['email' => 'user@hostname.com', 'flexible_cart_api' => 1] // additional params
);
echo $widget->getHtmlCode();
```

#### Pingback Processing

```php
use PaymentwallLaravel\Lib\Config;
use PaymentwallLaravel\Lib\Pingback;

Config::getInstance()->set([
    'api_type' => Config::API_CART
]);

$pingback = new Pingback($_GET, $_SERVER['REMOTE_ADDR']);
if ($pingback->validate(true)) {
  $products = $pingback->getProducts();
  if ($pingback->isDeliverable()) {
  // deliver products from the cart
  } else if ($pingback->isCancelable()) {
  // withdraw products from the cart
  } else if ($pingback->isUnderReview()) {
  // set "pending" status to order
  } 
  echo 'OK'; // Paymentwall expects response to be OK, otherwise the pingback will be resent
} else {
  echo $pingback->getErrorSummary();
}
```

## Brick

#### Initializing Paymentwall
```php
use PaymentwallLaravel\Lib\Config;

Config::getInstance()->set([
	'public_key' => 'YOUR_PUBLIC_KEY',
	'private_key' => 'YOUR_PRIVATE_KEY'
]);
```

#### Tokenize payment details with default form

- STEP 1: [FRONTEND](https://docs.paymentwall.com/integration/direct/brick/create-form)
  
    Paymentwall provides you a default form for Brick to collect payment details. Include Brick.js in your page in order to implement it.
  - Brick form:

    ```<script src="https://api.paymentwall.com/brick/1.6/build/brick.1.6.0.min.js"></script>```
  - Create a div tag with id, keep the consistency with the value of container in separate script tag.
  
    ```<div id="payment-form-container"></div>```
  - This tutorial uses a test project key which is also available to test in the section below. Remember to replace the value of param public_key with your own Brick project key.
  
  ```
    <script src="https://api.paymentwall.com/brick/1.6/build/brick.1.6.0.min.js"></script>
    <div id="payment-form-container"> </div>
      <script>
        var brick = new Brick({
          public_key: 'YOUR_PUBLIC_KEY', // please update it to Brick live key before launch your project
          amount: 9.99,
          currency: 'USD',
          container: 'payment-form-container',
          action: '/YOUR-CHARGE-ACTION',
          form: {
            merchant: 'Paymentwall',
            product: 'Gold Membership',
            pay_button: 'Pay',
            show_zip: true, // show zip code 
            show_cardholder: true, // show card holder name,
            lang: 'en'
          }
        });
    
        brick.showPaymentForm(function(data) {
          //handle success
        }, function(errors) {
          // handle errors
        });
      </script>
  ```

- STEP 2: Charge
    ```php
    use PaymentwallLaravel\Lib\Charge;
    
    $parameters = $request->all();
    $cardInfo = array(
        'email' => $parameters['email'],
        'amount' => 9.99,
        'currency' => 'USD',
        'token' => $parameters['brick_token'],
        'fingerprint' => $parameters['brick_fingerprint'],
        'description' => 'Order #123'
    );
    
    if (isset($parameters['brick_charge_id']) AND isset($parameters['brick_secure_token'])) {
        $cardInfo['charge_id'] = $parameters['brick_charge_id'];
        $cardInfo['secure_token'] = $parameters['brick_secure_token'];
    }
    
    $charge = new Charge();
    $charge->create($cardInfo);
    $responseData = json_decode($charge->getRawResponseData(),true);
    $response = $charge->getPublicData();
    
    if ($charge->isSuccessful() AND empty($responseData['secure'])) {
        if ($charge->isCaptured()) {
        // deliver a product
        } elseif ($charge->isUnderReview()) {
        // decide on risk charge
        }
    } elseif (!empty($responseData['secure'])) {
        $response = json_encode(array('secure' => $responseData['secure']));
    } else {
        $errors = json_decode($response, true);
    }
    
    echo $response;
    ```

#### Charge - refund

```php
use PaymentwallLaravel\Lib\Charge;

$charge = new Charge('CHARGE_ID');
$charge->refund();

echo $charge->isRefunded();
```

#### Subscription

```php
use PaymentwallLaravel\Lib\Subscription;

$parameters = $request->all();
$subscription = new Subscription();
$subscription->create([
	'token' => $parameters['brick_token'],
	'email' => $parameters['email'],
	'currency' => 'USD',
	'amount' => 10,
	'fingerprint' => $parameters['brick_fingerprint'],
	'plan' => 'product_123',
	'description' => 'Order #123',
	'period' => 'week',
	'period_duration' => 2,
	// if trial, add following parameters
	'trial[amount]' => 1,
	'trial[currency]' => 'USD',
	'trial[period]'   => 'month',
	'trial[period_duration]' => 1
]);

echo $subscription->getId();
```

#### Subscription - cancel

```php
use PaymentwallLaravel\Lib\Subscription;

$subscription = new Subscription('SUBSCRIPTION_ID');
$subscription->cancel();

echo $subscription->isActive();
```

### Signature calculation - Widget

```php
use PaymentwallLaravel\Lib\Signature\SignatureWidget;

$widgetSignatureModel = new SignatureWidget();
echo $widgetSignatureModel->calculate(
	[], // widget params
	2 // signature version
);
```

### Signature calculation - Pingback

```php
use PaymentwallLaravel\Lib\Signature\SignaturePingback;

$pingbackSignatureModel = new SignaturePingback();
echo $pingbackSignatureModel->calculate(
	[], // pingback params
	1 // signature version
);
```

## Mobiamo

#### Initializing Paymentwall
```php
use PaymentwallLaravel\Lib\Config;

Config::getInstance()->set([
	'public_key' => 'YOUR_PROJECT_KEY',
	'private_key' => 'YOUR_SECRET_KEY'
]);
```

#### Get a token
```php
use PaymentwallLaravel\Lib\Mobiamo;

$model = new Mobiamo();
$tokenParams = [
	'uid' => 'test'
]
$response = $model->getToken($tokenParams);
if (!empty($response['success'])) {
	// Store this token and expire time (default is 86400s) to use in all next requests
	/* Example of success response: 
		[
			'success' => 1, 
			'token' => 'randomString', 
			'expire_time' => 86400
		]
	*/
	var_dump($response['token']);
	var_dump($response['expire_time']);
} else {
	var_dump($response['error']);
	var_dump($response['code']);
}
```

#### Init payment
```php
use PaymentwallLaravel\Lib\Mobiamo;

$model = new Mobiamo();
$initParams = [
	'uid' => 'test', 
	'amount' => 1, 
	'currency' => 'GBP', //currency of payment in ISO 4217 format
	'country' => 'GB', //country of payment in ISO alpha-2 format
	'product_id' => 123, //product id of payment
	'product_name' => 'test_product_name', //product name of payment
	'msisdn' => '447821677123', //optional - phone number of user in internaltional format
	'carrier' => '19', //mandatory in some countries - Given id of user's operator
	'mcc' => '262', //optional - mobile country code of user
	'mnc' => '007', //optional - mobile netword code of user
	'is_recurring' => 1, //optional and only available in some countries - value: 1/0 - determine if this payment is recurring subscription
	'period' => 'd', //mandatory if is_recurring = 1 - value: d (day) - w (week) - m (month) - period of the recurring
	'period_value' => 1 //mandatory if is_recurring = 1 - value: positive number - value of the recurring period
];
//token returned from get token step above
$response = $model->initPayment($token, $initParams);
if (!empty($response['success'])) {
	/** example of success response: 
		[
			'success' => true,
			'ref' => 'w118678712', //reference id of payment.
			'flow' => 'code', //next flow of this payment. values can be: code/pinless - user send sms contain keyword to shortcode in instructions/ msisdn - user input phone number / redirect - redirect user to redirect_url in intructions
			'price' => [
				'amount' => 1,
				'currency' => 'GBP',
				'formatted' => 'GBP 1.00',
				'carriers' => [
					  0 => [
					    'id' => 19,
					    'name' => 'O2',
					  ],
					],
				],
			'instructions' => [
				'keyword' => 'test_keyword', //return if flow = code/pinless - sms message content for user to send
				'shortcode' => '123456', //return if flow = code/pinless - the number user should send message to
				'redirect_url' => 'http://google.com' //return if flow = redirect - url user should be redirected to
			]
			'product_name' => 'test_product_name',
		]
	*/
	//Store the parameter ref
} else {
	var_dump($response['error']);
	var_dump($response['code']);
}
```

#### Process payment (Use this request if previous response has flow = code/msisdn)
```php
use PaymentwallLaravel\Lib\Mobiamo;

$model = new Mobiamo();
$processParams = [
	'uid' => 'test', 
	'ref' => 'w118678712', //reference id returned from init request 
	'flow' => 'code', //flow returned from init request
	'data' => 'ABCDEF' //value can be: code user received after sending message / phone number of user
];
//token returned from get token step above
$response = $model->processPayment($token, $processParams);
if (!empty($response['success'])) {
	/** example of success response: 
		[
			'success' => true,
			'flow' => 'redirect', //Only return if this payment requires next processing step. values can be: code - user send keyword to shortcode in instructions/ msisdn - user input phone number / redirect - redirect user to redirect_url in intructions / 			
			'instructions' => [
				'keyword' => 'test_keyword', //return if flow = code/pinless - sms message content for user to send
				'shortcode' => '123456', //return if flow = code/pinless - the number user should send message to
				'redirect_url' => 'http://google.com' //return if flow = redirect - url user should be redirected to
			]
		]
	*/
} else {
	var_dump($response['error']);
	var_dump($response['code']);
}
```

#### Get payment info
```php
use PaymentwallLaravel\Lib\Mobiamo;

$model = new Mobiamo();
$getPaymentParams = [
	'uid' => 'test', 
	'ref' => 'w118678712', //reference id returned from init request 
];
//token returned from get token step above
$response = $model->processPayment($token, $getPaymentParams);
if (!empty($response['success'])) {
	/** example of success response: 
		[
			'success' => true,
			'completed' => true, //value: true/false - indicate this payment was already successfull or not
			'amount' => 1,
			'currency' => "GBP",
			'country' => "GB",
			'product_name' => "test_product_name",
			'msisdn' => "447821677123",
			'ref' => "w118678712"
		]
	*/
} else {
	var_dump($response['error']);
	var_dump($response['code']);
}
```
