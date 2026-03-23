<?php

interface BankInterface
{
    public function getBankName(): string;
}
enum BankType: string implements BankInterface
{
    case CentralBankofEgypt = 'CentralBankofEgypt';
    case CIB = 'CIB';
    case HSBC = 'HSBC';
    case Adib = 'Adib';
    case QNB = 'QNB';
    case NationalBankofEgypt = 'NationalBankofEgypt';
    case BanqueDuCaire = 'BanqueDuCaire';
    case BankofAlexandria = 'BankofAlexandria';
    case BankofCairo = 'BankofCairo';
    case BankofGiza = 'BankofGiza';
    case OtherBank = 'OtherBank';

    public function getBankName(): string
    {
        return $this->value;
    }
}