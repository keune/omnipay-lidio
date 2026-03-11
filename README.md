# Omnipay: Lidio

**Lidio gateway for the Omnipay PHP payment processing library**

[Omnipay](https://github.com/thephpleague/omnipay) driver for the [Lidio](https://lidio.com) Turkish payment gateway.

## Installation

```bash
composer require keune/omnipay-lidio
```

## Configuration

```php
use Omnipay\Omnipay;

$gateway = Omnipay::create('Lidio');

$gateway->setMerchantCode('YOUR_MERCHANT_CODE');
$gateway->setAuthorizationToken('YOUR_AUTH_TOKEN');
$gateway->setMerchantKey('YOUR_MERCHANT_KEY');   // for callback hash verification
$gateway->setApiPassword('YOUR_API_PASSWORD');   // for callback hash verification
$gateway->setTestMode(true);                     // false for production
```

Test environment: `https://test.lidio.com/api`
Production environment: `https://lidio.com/api`

## Usage

### Purchase (New Card)

```php
$response = $gateway->purchase([
    'transactionId' => 'order-123',
    'amount' => '100.00',
    'currency' => 'TRY',
    'clientIp' => '127.0.0.1',
    'clientPort' => '1234',
    'card' => [
        'firstName' => 'John',
        'lastName' => 'Doe',
        'number' => '4355084355084358',
        'expiryMonth' => '12',
        'expiryYear' => '2026',
        'cvv' => '000',
    ],
    'customerInfo' => [
        'email' => 'john@example.com',
        'customerId' => '12345',
        'name' => 'John Doe',
        'phone' => '5555555555',
    ],
    'basketItems' => [
        [
            'name' => 'Product 1',
            'category1' => 'Electronics',
            'quantity' => 1,
            'unitPrice' => 100,
            'criticalCategory' => 'Other',
            'isParticipationBankingCompatible' => true,
            'itemType' => 'Physical',
        ],
    ],
    'use3DSecure' => false,
    'saveAfterSuccess' => false,
    'installmentCount' => 0,
    'posAccountId' => 1,
])->send();

if ($response->isRedirect()) {
    // 3D Secure / BKM / GarantiPay etc. - redirect the customer
    // Option 1: Use Omnipay's built-in redirect (POST form)
    $response->redirect();

    // Option 2: Output the raw HTML redirect form from Lidio
    // echo $response->getRedirectForm();
} elseif ($response->isSuccessful()) {
    echo 'Transaction reference: ' . $response->getTransactionReference();
} else {
    echo 'Error: ' . $response->getMessage();
    echo 'Detail: ' . $response->getResultDetail();
}
```

### Purchase (Stored Card)

```php
$response = $gateway->purchase([
    'transactionId' => 'order-456',
    'amount' => '50.00',
    'currency' => 'TRY',
    'clientIp' => '127.0.0.1',
    'token' => 'card-token-from-lidio',
    'customerInfo' => [
        'email' => 'john@example.com',
        'customerId' => '12345',
        'name' => 'John Doe',
        'phone' => '5555555555',
    ],
    'basketItems' => [/* ... */],
])->send();
```

### Complete Purchase (3D Secure callback)

After 3D Secure redirect, the user's browser is redirected back to your `returnUrl` with query string parameters (`OrderId`, `SystemTransId`, `Result`, `TotalAmount`, `Hash`, etc.). You must verify the hash and then call `completePurchase` to finalize the payment:

```php
// Verify the callback hash first
$hashData = $orderId . ':' . $merchantKey . ':' . number_format($totalAmount, 2, '.', '') . ':' . $result . ':' . $customerIdOrEmail;
$expectedHash = base64_encode(hash('sha256', $hashData, true));

if ($hash !== $expectedHash || $result !== '3DSuccess') {
    // Hash mismatch or 3D failed — do not proceed
    die('Verification failed');
}

// Finalize the payment
$response = $gateway->completePurchase([
    'transactionId' => 'order-123',
    'transactionReference' => '12345678', // systemTransId from callback
    'amount' => '100.00',
    'currency' => 'TRY',
    'paymentInstrument' => 'newCard',
    'clientIp' => $_SERVER['REMOTE_ADDR'],
])->send();

if ($response->isSuccessful()) {
    echo 'Payment completed!';
} else {
    echo 'Error: ' . $response->getMessage();
    echo 'Detail: ' . $response->getResultDetail();
}
```

For CVV/OTP verification flows (when `ProcessPayment` returns `VerificationRequired`):

```php
$response = $gateway->completePurchase([
    'transactionId' => 'order-123',
    'transactionReference' => $systemTransId,
    'amount' => '100.00',
    'currency' => 'TRY',
    'paymentInstrument' => 'storedCard',
    'paymentInstrumentInfo' => [
        'storedCard' => [
            'cvv' => '123',
            'otp' => '999999',
        ],
    ],
    'clientIp' => $_SERVER['REMOTE_ADDR'],
])->send();
```

### Capture (Post-Authorization)

```php
$response = $gateway->capture([
    'transactionId' => 'order-123',
    'amount' => '100.00',
    'currency' => 'TRY',
    'clientIp' => '127.0.0.1',
])->send();
```

### Void (Cancel)

```php
$response = $gateway->void([
    'transactionId' => 'order-123',
])->send();
```

### Refund

```php
$response = $gateway->refund([
    'transactionId' => 'order-123',
    'refundTransId' => '0001',
    'amount' => '50.00',
    'currency' => 'TRY',
    'clientIp' => '127.0.0.1',
])->send();
```

### Fetch Transaction (Payment Inquiry)

```php
$response = $gateway->fetchTransaction([
    'transactionId' => 'order-123',
    'amount' => '100.00',
    'paymentInstrument' => 'newCard',
    'posAccountId' => 1,
])->send();
```

### Get Installment Info

```php
$response = $gateway->getInstallmentInfo([
    'bin' => '43550843',
    'amount' => '100.00',
    'posId' => 0,
    'cardCategory' => 'AllCards',
])->send();

if ($response->isSuccessful()) {
    $posList = $response->getPosList();
}
```

### Get Bank of BIN Number

```php
$response = $gateway->getBankOfBinNumber([
    'bin' => '435508',
    'clientType' => 'Web',
    'clientIp' => '127.0.0.1',
])->send();

if ($response->isSuccessful()) {
    $bankCode = $response->getBankCode();
    $cardType = $response->getCardType();           // e.g. 'Visa', 'Mastercard'
    $isDebit = $response->isDebitCard();
    $isBusinessCard = $response->isBusinessCard();
    $cardProgram = $response->getCardProgramName();
}
```

### Card Management

#### Save a Card

```php
$response = $gateway->createCard([
    'card' => [
        'firstName' => 'John',
        'lastName' => 'Doe',
        'number' => '4355084355084358',
        'expiryMonth' => '12',
        'expiryYear' => '2026',
    ],
    'customerInfo' => [
        'customerID' => '12345',
        'email' => 'john@example.com',
        'name' => 'John Doe',
        'phone' => '5555555555',
    ],
    'verificationOtp' => '123456',
    'clientIp' => '127.0.0.1',
])->send();

if ($response->isSuccessful()) {
    $cardToken = $response->getCardReference();
}
```

#### List Cards

```php
$response = $gateway->fetchCards([
    'customerId' => '12345',
    'phone' => '5555555555',
    'email' => 'john@example.com',
])->send();

if ($response->isSuccessful()) {
    $cards = $response->getCards();
}
```

#### Delete a Card

```php
$response = $gateway->deleteCard([
    'cardReference' => 'card-token-to-delete',
    'customerId' => '12345',
    'clientIp' => '127.0.0.1',
])->send();
```

## Supported Methods

| Omnipay Method       | Lidio Endpoint         | Description                  |
|----------------------|------------------------|------------------------------|
| `purchase()`         | `ProcessPayment`       | Process a payment            |
| `completePurchase()` | `FinishPaymentProcess` | Complete 3D Secure payment   |
| `capture()`          | `PostAuth`             | Capture a pre-authorized payment |
| `void()`             | `Cancel`               | Cancel/void a transaction    |
| `refund()`           | `Refund`               | Refund a transaction         |
| `fetchTransaction()` | `PaymentInquiry`       | Query transaction status     |
| `getInstallmentInfo()` | `GetInstallmentInfo` | Get installment options      |
| `createCard()`       | `SaveCard`             | Save a card for future use   |
| `deleteCard()`       | `DeleteCard`           | Delete a stored card         |
| `fetchCards()`       | `GetCardList`          | List stored cards            |
| `getBankOfBinNumber()` | `GetBankOfBINNumber` | Get bank info by BIN number  |

## Parameter Mapping

Omnipay uses standardized parameter names. Here's how they map to Lidio's API:

| Omnipay Parameter      | Lidio Parameter    |
|------------------------|--------------------|
| `transactionId`        | `orderId`          |
| `transactionReference` | `systemTransId`    |
| `cardReference`        | `cardToken`        |
| `notifyUrl`            | `notificationUrl`  |
| `amount`               | `totalAmount`      |
| `token`                | `cardToken` (stored card payment) |

## Response Handling

All responses extend `AbstractLidioResponse`. A successful response has `result` equal to `Success`.

```php
$response = $gateway->purchase([/* ... */])->send();

$response->isSuccessful();          // true if result === 'Success'
$response->isRedirect();            // true when result is 'RedirectFormCreated' or 'VerificationRequired'
$response->getMessage();            // resultMessage
$response->getCode();               // result (e.g. 'Success', 'RedirectFormCreated', 'Refused')
$response->getResultDetail();       // resultDetail (e.g. 'CVVRequired', 'ThreeDSRedirectFormCreated')
$response->getTransactionReference(); // paymentInfo.systemTransId
$response->getTransactionId();      // paymentInfo.orderId

// Redirect-specific (PurchaseResponse only):
$response->getRedirectUrl();        // redirectFormParams.actionLink
$response->getRedirectData();       // redirectFormParams.paramList (as key-value array)
$response->getRedirectForm();       // redirectForm (raw HTML form string)
$response->isVerificationRequired(); // true when CVV/OTP needed
```

## Raw Payload

For advanced use cases, you can pass the `paymentInstrumentInfo` array directly instead of using Omnipay's `CreditCard` object:

```php
$response = $gateway->purchase([
    'transactionId' => 'order-789',
    'amount' => '100.00',
    'currency' => 'TRY',
    'paymentInstrument' => 'newCard',
    'paymentInstrumentInfo' => [
        'newCard' => [
            'processType' => 'sales',
            'cardInfo' => [
                'cardHolderName' => 'John Doe',
                'cardNumber' => '4355084355084358',
                'lastMonth' => 12,
                'lastYear' => 2026,
            ],
            'cvv' => '000',
            'use3DSecure' => true,
            'installmentCount' => 3,
            // ... other Lidio-specific fields
        ],
    ],
])->send();
```

## License

MIT
