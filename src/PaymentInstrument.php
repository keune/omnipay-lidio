<?php

namespace Keune\OmnipayLidio;

enum PaymentInstrument: string
{
    case StoredCard = 'StoredCard';
    case NewCard = 'NewCard';
    case BKMExpress = 'BKMExpress';
    case GarantiPay = 'GarantiPay';
    case MaximumMobil = 'MaximumMobil';
    case Emoney = 'Emoney';
    case WireTransfer = 'WireTransfer';
    case DirectWireTransfer = 'DirectWireTransfer';
    case InstantLoan = 'InstantLoan';
    case MarketplaceBalance = 'MarketplaceBalance';
    case Ideal = 'Ideal';
    case Sofort = 'Sofort';
    case Sepa = 'Sepa';
    case IATAPay = 'IATAPay';
    case HepsiPay = 'HepsiPay';
    case YKBWorldPay = 'YKBWorldPay';
    case ApplePay = 'ApplePay';
}
