<?php

namespace Jtl\Connector\Core\Controller;

use Jtl\Connector\Core\Model\CustomerOrder;
use Jtl\Connector\Core\Model\CustomerOrderBillingAddress;
use Jtl\Connector\Core\Model\CustomerOrderItem;
use Jtl\Connector\Core\Model\CustomerOrderShippingAddress;
use Jtl\Connector\Core\Model\Identity;
use Jtl\Connector\Core\Model\Product;
use Jtl\Connector\Core\Model\QueryFilter;

class CustomerOrderController extends AbstractController implements PullInterface
{
    public function pull(QueryFilter $queryFilter): array
    {
        $endpointUrl = $this->getEndpointUrl('getOrders');
        $client = $this->getHttpClient();

        $orders = [];

        try {
            $response = $client->request('GET', $endpointUrl);

            $statusCode = $response->getStatusCode();
            $data = $response->toArray();

            if ($statusCode !== 200 || !isset($data['success']) || $data['success'] !== true) {
                $this->logger->error('Endpoint getOrders error!');
                return [];
            }

            foreach ($data['orders'] as $orderData) {
                $email = $orderData['customer']['email'];

                $identity = new Identity($orderData['id'], 0);
                $order = new CustomerOrder();
                $order->setId($identity);
                $order->setOrderNumber($orderData['orderNumber']);
                $order->setLanguageIso('de');
                $order->setCurrencyIso($orderData['currencyIso']);
                $order->setCreationDate(\DateTime::createFromFormat('U', $orderData['orderDateUnix']));
                $order->setCustomerNote($orderData['customerComment']??'');
                $order->setNote('Bei Dropshipping: "Kunden-Ref.Nr./Auftragsreferenz"');

                // Shipping address
                $shippingAddress = new CustomerOrderShippingAddress();
                $shippingAddress->setCountryIso($orderData['delivery']['country']);
                $shippingAddress->setFirstName($orderData['delivery']['firstName']);
                $shippingAddress->setLastName($orderData['delivery']['lastName']);
                $shippingAddress->setCompany($orderData['delivery']['company']??'');
                $shippingAddress->setExtraAddressLine($orderData['delivery']['extraAddressLine']??'');
                $shippingAddress->setCity($orderData['delivery']['city']);
                $shippingAddress->setStreet($orderData['delivery']['street']);
                $shippingAddress->setZipCode($orderData['delivery']['zip']);
                $shippingAddress->setEMail($email);
                $shippingAddress->setCustomerId(new Identity($orderData['customer']['id']??'', 0));
                $order->setShippingAddress($shippingAddress);

                // Billing address
                $billingAddress = new CustomerOrderBillingAddress();
                $billingAddress->setCountryIso($orderData['customer']['country']);
                $billingAddress->setFirstName($orderData['customer']['firstName']);
                $billingAddress->setLastName($orderData['customer']['lastName']);
                $billingAddress->setCompany($orderData['customer']['company']??'');
                $billingAddress->setCity($orderData['customer']['city']);
                $billingAddress->setStreet($orderData['customer']['street']);
                $billingAddress->setExtraAddressLine($orderData['customer']['extraAddressLine']??'');
                $billingAddress->setZipCode($orderData['customer']['zip']);
                $billingAddress->setEMail($email);
                $order->setBillingAddress($billingAddress);

                // Items
                foreach ($orderData['items'] as $item) {
                    $customerOrderItem = new CustomerOrderItem();
                    $customerOrderItem->setId(new Identity($item['productId'], 0));
                    $customerOrderItem->setSku($item['sku']);
                    $customerOrderItem->setName($item['name']);
                    $customerOrderItem->setQuantity($item['quantity']);
                    $customerOrderItem->setPriceGross($item['totalPrice']);
                    $customerOrderItem->setPrice($item['totalPriceNet']);
                    $customerOrderItem->setVat($item['vat']);
                    $customerOrderItem->setNote('e.g. "ANP_SONDERPREIS=0|ANP_BEIGABE=0|ANP_KREDITKAUF=0"');
                    $order->addItem($customerOrderItem);
                }

                $order->setTotalSum($orderData['totalSum']);
                $order->setTotalSumGross($orderData['totalSumGross']);

                $orders[] = $order;
            }

        } catch (\Throwable $e) {
            throw new \RuntimeException('HTTP request failed: ' . $e->getMessage(), 0, $e);
        }

        return $orders;
    }

    protected function updateModel(Product $model): void
    {
        // nothing to-do here
    }
}