TokenToCardInfoInquiry
======================

post

/api/TokenToCardInfoInquiry

'TokenToCardInfoInquiry' method is used to get a specific stored card information.

[

Request

](#Request)
-----------------------

Security: API Key

An API key is a token that you provide when making API calls. Include the token in a header parameter called `Authorization`.

Example: `Authorization: 123`

### [

Headers

](#request-headers)

MerchantCode

string

required

### [

Body

](#request-body)

application/jsonapplication/json-patch+jsontext/jsonapplication/\*+json

application/json

Stored card information inquiry request

email

string or null

Email address identifying the customer of merchant.

customerId

string or null

CustomerID identifying the customer of the merchant.

cardToken

string or null

required

CardToken or masked card number. Masked card number is the card number masked with star (\*) except first 6 and last 4 characters. In case of american express card, only first 6 and the last 3 character will not be masked.

clientType

string

Allowed values:

WebAndroidIOSUnknownMobileWeb

clientIp

string or null

IP address of customer machine used for this operation.

clientPort

integer<int32> or null

clientUserAgent

string or null

User agent info of customer.

clientInfo

string or null

Within the clientInfo parameter, merchants should sent below mentioned client information structured as JSON; as a string value in the API for the clientInfo filed. The corresponding client info can be collected by merchant itself or Mobilexpress/Lidio client script (to be provided upon request) can be used to collect and prepare the values to be provided for all these client related fields (clientInfo, clientIp, clientPort, clientType, clientUserAgent) in the API requests.

Key

Description

userLang

machineId

clientTimeZone

clientTime

screenHeight

screenWidth

colorDepth

clientType

entrySpeed

acceptHeader

javaEnabled

javaScriptEnabled

[

Responses

](#Responses)
---------------------------

200

### [

Body

](#response-body)

text/plainapplication/jsontext/json

text/plain

responses

/

200

Result of stored card information inquiry

result

string or null

Result Values

Description

Success

Card info returned successfully.

CardNotFound

No card info found with the given CustomerId/Email and CardToken.

InvalidEmail

Invalid or empty email. If Email is unique customer identifier for the Merchant, empty Email is not allowed.

InvalidCustomerID

If CustomerId is unique customer identifier for the Merchant, empty CustomerID is not allowed.

InvalidCredential

Invalid Authorization or MerchantCode values in header or IP address of the API call may be out of defined IP’s (for production env.) for the merchant.

SystemError

An unexpected error has been occurred. Contact with Mobilexpress technical administrators.

resultMessage

string or null

Result message given by Mobilexpress.

customerID

string or null

CustomerID identifying the customer of the merchant.

cardInfo

object

Information of the credit card

isDefault

boolean

Indicates whether the relevant card is the default payment card.

cardHolderName

string or null

Cardholder name entered when card saving.

maskedCardNumber

string or null

Masked card number is the card number masked with star (\*) except first 6 and last 4 characters. In case of american express card, only first 6 and the last 3 character will not be masked.

cardToken

string or null

Unique card identifier represents credit card number. This token can be used to access stored credit card info while using other API methods.

isExpired

boolean or null

Boolean value representing whether the card is expired or not.

consentApproved

boolean or null

If the card is saved through hosted pages, an extra consent can be got from user according to the merchant’s configuration. This parameter shows whether the consent is approved or not for this specific card.

finishPaymentRequired

boolean

Boolean value representing whether the card needs OTP and/or CVV verification to complete “NON-3DS” payments. If it is True and payment is NON-3DS, ProcessPayment will return VerificationRequired result (ResultDetail: OTPRequired/CVVRequired/CVVandOTPRequired) upon the system security rules. Merchant needs to run corresponding verification user flows and then call FinishPaymentProcess to complete payment process. In 3DS scenarios, it has no effect either the value is True or False, payment flow will be completed as 3DS with no extra verification needed. In business flows where payment is NON-3DS and OTP/CVV verification flows are not applicable (e.g. Recurring payments – user not present), it is recommended to either complete one non3DS payment during the card selection when user is still present or (if not doable) exclude these cards with True value from selection list for these scenarios.

expiresIn90Days

boolean or null

Boolean value representing whether the card is will be expired in 90 days or not.

lastYear

integer<int32> or null

if merchants have a specific need for their processes, Mobilexpress can return card expire date. Last year of card expire date will be returned with this field, only If related merchant configuration is set.

lastMonth

integer<int32> or null

if merchants have a specific need for their processes, Mobilexpress can return card expire date. Last month of card expire date will be returned with this field, only If related merchant configuration is set.

cardNamebyUser

string or null

Card name given by the user for the stored card. A standart name (bank name and last 4 digits) will be assigned for the card that user did not give a name.

bankCode

string or null

Bank Code

[Click for bank codes](https://test.mobilexpress.com.tr/icerik/id/1268/Banka-EFT-Kodlari?v=1)

isDebitCard

boolean or null

Specifies whether the card is debit card or not. If ‘true’, card is debit card.

cardProgramName

string or null

String representing card program. [Possible values](https://www.mobilexpress.com.tr/icerik/id/1282/KartProgramlari?v=1)

isBusinessCard

boolean or null

Specifies whether the card is business card or not. If ‘true’, card is business card.

cardType

string or null

String representing card type. Possible values ; ‘Unknown’, ‘Mastercard’, ‘Visa’, ‘Amex’,’Troy’, ‘JCB’, ‘DinersClub’, ‘Maestro’,’Discover'

bin

string or null

\[DEPRECATED\] 7th and 8th digits of card number

binNumber

string or null

The card's BIN number. 6 or 8 digits, depending on the company setting.

kkbVerified

boolean or null

Defines if the corresponding card is already verified via KKB official services by the Merchant. So this information will be flagged in payment/card record in our Systems.

cardSavedDate

string<date-time> or null

The date when the card was first registered in the system. Returned in ISO 8601 format: "yyyy-MM-ddTHH:mm:ss.fffZ". Example: "2025-01-02T10:29:45.527Z"