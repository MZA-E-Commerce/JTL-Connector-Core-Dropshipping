<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Definition\IdentityType;
use Jtl\Connector\Core\Model\Customer;

/**
 * Class CustomerFactory
 *
 * @package Jtl\Connector\Core\Model\Generator
 */
class CustomerFactory extends AbstractModelFactory
{
    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Customer::class;
    }

    /**
     * @return mixed[]
     * @throws \Exception
     */
    protected function makeFakeArray(): array
    {
        return [
            'id'                        => $this->makeIdentityArray(IdentityType::CUSTOMER),
            'customerGroupId'           => $this->makeIdentityArray(IdentityType::CUSTOMER_GROUP),
            'accountCredit'             => $this->faker->numberBetween(),
            'birthday'                  => $this->dateBetween('-40 years', '-30 years'),
            'city'                      => $this->faker->city,
            'company'                   => $this->faker->company,
            'countryIso'                => $this->faker->countryCode,
            'creationDate'              => self::dateBetween('-1 day'),
            'customerNumber'            => $this->faker->numberBetween(1),
            'deliveryInstruction'       => '',
            'discount'                  => '',
            'eMail'                     => $this->faker->safeEmail,
            'extraAddressLine'          => '',
            'fax'                       => '',
            'firstName'                 => $this->faker->firstName,
            'hasCustomerAccount'        => $this->faker->boolean,
            'hasNewsletterSubscription' => '',
            'isActive'                  => $this->faker->boolean,
            'lastName'                  => $this->faker->lastName,
            'mobile'                    => $this->faker->phoneNumber,
            'note'                      => $this->faker->realText(),
            'origin'                    => '',
            'phone'                     => $this->faker->phoneNumber,
            'salutation'                => $this->faker->randomElement(['Herr', 'Frau', '']),
            'state'                     => '',
            'street'                    => $this->faker->streetAddress,
            'title'                     => $this->faker->title,
            'vatNumber'                 => $this->faker->uuid,
            'websiteUrl'                => $this->faker->safeEmailDomain,
            'zipCode'                   => $this->faker->postcode,
            'languageIso'               => $this->faker->languageCode,
        ];
    }
}
