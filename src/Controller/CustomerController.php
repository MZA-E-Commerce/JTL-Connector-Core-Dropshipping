<?php

namespace Jtl\Connector\Core\Controller;

use Jtl\Connector\Core\Model\Customer;
use Jtl\Connector\Core\Model\Identity;
use Jtl\Connector\Core\Model\Product;
use Jtl\Connector\Core\Model\QueryFilter;

class CustomerController extends AbstractController implements PullInterface
{
    protected function updateModel(Product $model): void
    {
        // TODO: Implement updateModel() method.
    }

    public function pull(QueryFilter $queryFilter): array
    {
        $result = [];

        $endpointUrl = $this->getEndpointUrl('getCustomers');
        $client = $this->getHttpClient();

        try {
            $response = $client->request('GET', $endpointUrl);

            $statusCode = $response->getStatusCode();
            $data = $response->toArray();

            if ($statusCode !== 200 || !isset($data['success']) || $data['success'] !== true) {
                $this->logger->error('Endpoint getCustomers error!');
                return [];
            }

            foreach ($data['customers'] as $customer) {
                $customerGroup = self::CUSTOMER_TYPE_MAPPINGS_REVERSE[$customer['customerGroup']] ?? '';
                $birthday = \DateTime::createFromFormat('U', $customer['birthdayUnix']);
                $jtlCustomer = new Customer();
                $jtlCustomer->setId(new Identity($customer['id'], 0));
                $jtlCustomer->setLanguageIso($customer['languageIso']);
                $jtlCustomer->setCustomerNumber($customer['id']??'');
                $jtlCustomer->setCustomerGroupId(new Identity($customerGroup, 0));
                $jtlCustomer->setFirstName($customer['firstName']);
                $jtlCustomer->setLastName($customer['lastName']);
                $jtlCustomer->setCompany($customer['company']??'');
                $jtlCustomer->setBirthday($birthday?:null);
                $jtlCustomer->setEmail($customer['email']);
                $jtlCustomer->setStreet($customer['street']);
                $jtlCustomer->setExtraAddressLine($customer['addressLine']??'');
                $jtlCustomer->setZipCode($customer['zip']);
                $jtlCustomer->setCity($customer['city']);
                $jtlCustomer->setPhone($customer['phone']??'');
                $jtlCustomer->setCountryIso($customer['countryIso']??'DE');
                $jtlCustomer->setHasNewsletterSubscription($customer['newsletter']??false);
                $result[] = $jtlCustomer;
            }
        }
        catch (\Throwable $e) {
            throw new \RuntimeException('HTTP request failed: ' . $e->getMessage(), 0, $e);
        }

        return $result;
    }
}