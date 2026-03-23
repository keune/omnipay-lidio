<?php

namespace Keune\OmnipayLidio;

enum PaymentInstrument: string
{
    case StoredCard = 'storedCard';
    case NewCard = 'newCard';
    case BKMExpress = 'bkmExpress';
    case GarantiPay = 'garantiPay';
    case MaximumMobil = 'maximumMobil';
    case Emoney = 'emoney';
    case WireTransfer = 'wireTransfer';
    case DirectWireTransfer = 'directWireTransfer';
    case InstantLoan = 'instantLoan';
    case MarketplaceBalance = 'marketplaceBalance';
    case Ideal = 'ideal';
    case Sofort = 'sofort';
    case Sepa = 'sepa';
    case IATAPay = 'iataPay';
    case HepsiPay = 'hepsiPay';
    case YKBWorldPay = 'ykbWorldPay';
    case ApplePay = 'applePay';
}
