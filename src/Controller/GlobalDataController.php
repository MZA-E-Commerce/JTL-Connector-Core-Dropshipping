<?php

namespace Jtl\Connector\Core\Controller;

use Jtl\Connector\Core\Model\AbstractModel;
use Jtl\Connector\Core\Model\Currency;
use Jtl\Connector\Core\Model\CustomerGroup;
use Jtl\Connector\Core\Model\CustomerGroupI18n;
use Jtl\Connector\Core\Model\GlobalData;
use Jtl\Connector\Core\Model\Identity;
use Jtl\Connector\Core\Model\Language;
use Jtl\Connector\Core\Model\QueryFilter;
use Jtl\Connector\Core\Model\TaxRate;

class GlobalDataController implements PullInterface, PushInterface
{
    /**
     * @inheritDoc
     */
    public function pull(QueryFilter $queryFilter) : array
    {
        $result = [];

        $globalData = new GlobalData;

        // Languages
        $globalData->addLanguage(
            (new Language())->setId(new Identity('4faa508a23e3427889bfae0561d7915d'))
                ->setLanguageISO('ger')
                ->setIsDefault(true)
                ->setNameGerman('Deutsch')
                ->setNameEnglish('German')
        );

        // Currencies
        $globalData->addCurrency(
            (new Currency())->setId(new Identity('56b0d7e12feb47838e2cd6c49f2cfd82'))
                ->setIsDefault(true)
                ->setName('Euro')
                ->setDelimiterCent(',')
                ->setDelimiterThousand('.')
                ->setFactor(1.0)
                ->setHasCurrencySignBeforeValue(false)
                ->setIso('EUR')
                ->setNameHtml('&euro;')
        );

        // CustomerGroups
        $globalData->addCustomerGroup(
            (new CustomerGroup())->setId(new Identity(AbstractController::CUSTOMER_TYPE_B2C))
                ->setIsDefault(true)
                ->setApplyNetPrice(false)
                ->addI18n((new CustomerGroupI18n())->setName('Endkunden')->setLanguageIso('ger'))
        );

        $globalData->addCustomerGroup(
            (new CustomerGroup())->setId(new Identity(AbstractController::CUSTOMER_TYPE_B2B))
                ->setIsDefault(false)
                ->setApplyNetPrice(true)
                ->addI18n((new CustomerGroupI18n())->setName('Händler')->setLanguageIso('ger'))
        );

        $globalData->addCustomerGroup(
            (new CustomerGroup())->setId(new Identity(AbstractController::CUSTOMER_TYPE_B2B_DROPSHIPPING))
                ->setIsDefault(false)
                ->setApplyNetPrice(true)
                ->addI18n((new CustomerGroupI18n())->setName('Dropshipping-Händler')->setLanguageIso('ger'))
        );

        // TaxRates

        $globalData->addTaxRate(
            (new TaxRate())->setId(new Identity('f1ec9220f3f64049926a83f5ba8df985'))
                ->setRate(19.0)->setCountryIso('DE')
        );

        $globalData->addTaxRate(
            (new TaxRate())->setId(new Identity('ec0a029a85554745aa42fb708d3c5c8c'))
                ->setRate(7.0)->setCountryIso('DE')
        );

        $result[] = $globalData;

        return $result;
    }

    public function push(AbstractModel ...$model): array
    {
        return [];
    }
}