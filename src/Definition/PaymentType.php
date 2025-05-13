<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Definition;

final class PaymentType
{
    public const
        ADYEN                = 'pm_adyen',
        ALIPAY               = 'pm_alipay',
        AMAPAY               = 'pm_amazon_payments',
        ASIAPAY              = 'pm_asiapay',
        ATOS                 = 'pm_atos',
        AUTHORIZE            = 'pm_authorize',
        BALANCED             = 'pm_balanced',
        BANK_TRANSFER        = 'pm_bank_transfer',
        CASH                 = 'pm_cash',
        CASH_ON_DELIVERY     = 'pm_cash_on_delivery',
        BILLPAY              = 'pm_billpay',
        BILLSAFE             = 'pm_billsafe',
        BPAY                 = 'pm_bpay',
        BRAINTREE            = 'pm_braintree',
        CHARGIFY             = 'pm_chargify',
        CITI_BANK            = 'pm_citi_bank',
        CLICKANDBUY          = 'pm_clickandbuy',
        COMMERZ_FINANZ       = 'pm_commerzfinanz',
        CREDITCALL           = 'pm_creditcall',
        CYBERSOURCE          = 'pm_cybersource',
        DATACASH             = 'pm_datacash',
        DIGICASH             = 'pm_digicash',
        DIRECT_DEBIT         = 'pm_direct_debit',
        DWOLLA               = 'pm_dwolla',
        EDY                  = 'pm_edy',
        ELAVON               = 'pm_elavon',
        EOS_CREDIT_CARD      = 'pm_eos_creditcard',
        EOS_DIRECT_BANKING   = 'pm_eos_direct',
        EOS_DIRECT_DEBIT     = 'pm_eos_direct_debit',
        EOS_EWALLET          = 'pm_eos_ewallet',
        EURONET_WORLDWIDE    = 'pm_euronet_worldwide',
        EWAY                 = 'pm_eway',
        FLOOZ                = 'pm_flooz',
        GLOBALCOLLECT        = 'pm_globalcollect',
        GOOGLE               = 'pm_google',
        HEIDELPAY            = 'pm_heidelpay',
        HSBC                 = 'pm_hsbc',
        IKOBO                = 'pm_ikobo',
        ILOXX                = 'pm_iloxx',
        INVOICE              = 'pm_invoice',
        IPAYMENT             = 'pm_ipayment',
        IP_PAYMENTS          = 'pm_ip_payments',
        KLARNA               = 'pm_klarna',
        MOBILPENGE           = 'pm_mobilpenge',
        MODUSLINK            = 'pm_moduslink',
        MOLLIE               = 'pm_mollie',
        MPP_GLOBAL_SOLUTIONS = 'pm_mpp_global_solutions',
        NETELLER             = 'pm_neteller',
        NOCHEX               = 'pm_nochex',
        NO_PAYMENT_REQUIRED  = 'pm_free',
        OGONE                = 'pm_ogone',
        PAYBOX               = 'pm_paybox',
        PAYEX                = 'pm_payex',
        PAYMATE              = 'pm_paymate',
        PAYMILL              = 'pm_paymill',
        PAYMORROW            = 'pm_paymorrow',
        PAYONEER             = 'pm_payoneer',
        PAYPAL               = 'pm_paypal_standard',
        PAYPAL_EXPRESS       = 'pm_paypal_express',
        PAYPAL_PLUS          = 'pm_paypal_plus',
        PAYPOINT             = 'pm_paypoint',
        PAYSAFECARD          = 'pm_paysafecard',
        PAYXPERT             = 'pm_payxpert',
        PAYZA                = 'pm_payza',
        PEPPERCOIN           = 'pm_peppercoin',
        PLAYSPAN             = 'pm_playspan',
        POPMONEY             = 'pm_popmoney',
        POSTFINANCE          = 'pm_postfinance',
        PREPAYMENT           = 'pm_prepayment',
        PROBILLER            = 'pm_probiller',
        RECURLY              = 'pm_recurly',
        SAFERPAY             = 'pm_saferpay',
        SAFETYPAY            = 'pm_safetypay',
        SAGE_GROUP           = 'pm_sage_group',
        SKRILL               = 'pm_skrill',
        SOFORT               = 'pm_sofort',
        SQUARE_INC           = 'pm_square',
        STRIPE               = 'pm_stripe',
        TFI_MARKETS          = 'pm_tfi_markets',
        TIMWE                = 'pm_timwe',
        VERIFONE             = 'pm_verifone',
        VINDICIA             = 'pm_vindicia',
        WEBMONEY             = 'pm_webmoney',
        WEPAY                = 'pm_wepay',
        WIRECARD             = 'pm_wirecard',
        WORLDPAY             = 'pm_worldpay';

    /** @var string[]|null */
    protected static ?array $types = null;

    /**
     * @param string $type
     *
     * @return bool
     */
    public static function isType(string $type): bool
    {
        return \in_array($type, self::getTypes(), true);
    }

    /**
     * @return array<string, string>
     */
    public static function getTypes(): array
    {
        if (\is_null(self::$types)) {
            /** @var array<string, string> $types */
            $types       = (new \ReflectionClass(self::class))->getConstants();
            self::$types = $types;
        }

        return self::$types;
    }
}
