<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

abstract class AbstractOrderAddress extends AbstractIdentity
{
    /** @var string City */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('city')]
    #[Serializer\Accessor(getter: 'getCity', setter: 'setCity')]
    protected string $city = '';

    /** @var string Company name */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('company')]
    #[Serializer\Accessor(getter: 'getCompany', setter: 'setCompany')]
    protected string $company = '';

    /** @var string Country ISO 3166-2 (2 letter Uppercase) */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('countryIso')]
    #[Serializer\Accessor(getter: 'getCountryIso', setter: 'setCountryIso')]
    protected string $countryIso = '';

    /** @var string Delivery instruction e.g. "c/o John Doe" */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('deliveryInstruction')]
    #[Serializer\Accessor(getter: 'getDeliveryInstruction', setter: 'setDeliveryInstruction')]
    protected string $deliveryInstruction = '';

    /** @var string E-Mail address */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('eMail')]
    #[Serializer\Accessor(getter: 'getEMail', setter: 'setEMail')]
    protected string $eMail = '';

    /** @var string Extra address line e.g. 'Apartment 2.5' */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('extraAddressLine')]
    #[Serializer\Accessor(getter: 'getExtraAddressLine', setter: 'setExtraAddressLine')]
    protected string $extraAddressLine = '';

    /** @var string Fax number */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('fax')]
    #[Serializer\Accessor(getter: 'getFax', setter: 'setFax')]
    protected string $fax = '';

    /** @var string First name */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('firstName')]
    #[Serializer\Accessor(getter: 'getFirstName', setter: 'setFirstName')]
    protected string $firstName = '';

    /** @var string Last name */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('lastName')]
    #[Serializer\Accessor(getter: 'getLastName', setter: 'setLastName')]
    protected string $lastName = '';

    /** @var string Mobile phone number */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('mobile')]
    #[Serializer\Accessor(getter: 'getMobile', setter: 'setMobile')]
    protected string $mobile = '';

    /** @var string Phone number */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('phone')]
    #[Serializer\Accessor(getter: 'getPhone', setter: 'setPhone')]
    protected string $phone = '';

    /** @var string Salutation e.g. 'Mr.' */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('salutation')]
    #[Serializer\Accessor(getter: 'getSalutation', setter: 'setSalutation')]
    protected string $salutation = '';

    /** @var string State */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('state')]
    #[Serializer\Accessor(getter: 'getState', setter: 'setState')]
    protected string $state = '';

    /** @var string Street + streetnumber */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('street')]
    #[Serializer\Accessor(getter: 'getStreet', setter: 'setStreet')]
    protected string $street = '';

    /** @var string Title e.g. ("Prof. Dr.") */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('title')]
    #[Serializer\Accessor(getter: 'getTitle', setter: 'setTitle')]
    protected string $title = '';

    /** @var string Zip / postal code */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('zipCode')]
    #[Serializer\Accessor(getter: 'getZipCode', setter: 'setZipCode')]
    protected string $zipCode = '';

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return $this
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string Company name
     */
    public function getCompany(): string
    {
        return $this->company;
    }

    /**
     * @param string $company Company name
     *
     * @return $this
     */
    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return string Country ISO 3166-2 (2 letter Uppercase)
     */
    public function getCountryIso(): string
    {
        return $this->countryIso;
    }

    /**
     * @param string $countryIso Country ISO 3166-2 (2 letter Uppercase)
     *
     * @return $this
     */
    public function setCountryIso(string $countryIso): self
    {
        $this->countryIso = $countryIso;

        return $this;
    }

    /**
     * @return string Delivery instruction e.g. "c/o John Doe"
     */
    public function getDeliveryInstruction(): string
    {
        return $this->deliveryInstruction;
    }

    /**
     * @param string $deliveryInstruction Delivery instruction e.g. "c/o John Doe"
     *
     * @return $this
     */
    public function setDeliveryInstruction(string $deliveryInstruction): self
    {
        $this->deliveryInstruction = $deliveryInstruction;

        return $this;
    }

    /**
     * @return string E-Mail address
     */
    public function getEMail(): string
    {
        return $this->eMail;
    }

    /**
     * @param string $eMail E-Mail address
     *
     * @return $this
     */
    public function setEMail(string $eMail): self
    {
        $this->eMail = $eMail;

        return $this;
    }

    /**
     * @return string Extra address line e.g. 'Apartment 2.5'
     */
    public function getExtraAddressLine(): string
    {
        return $this->extraAddressLine;
    }

    /**
     * @param string $extraAddressLine Extra address line e.g. 'Apartment 2.5'
     *
     * @return $this
     */
    public function setExtraAddressLine(string $extraAddressLine): self
    {
        $this->extraAddressLine = $extraAddressLine;

        return $this;
    }

    /**
     * @return string Fax number
     */
    public function getFax(): string
    {
        return $this->fax;
    }

    /**
     * @param string $fax Fax number
     *
     * @return $this
     */
    public function setFax(string $fax): self
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * @return string First name
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName First name
     *
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string Last name
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName Last name
     *
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string Mobile phone number
     */
    public function getMobile(): string
    {
        return $this->mobile;
    }

    /**
     * @param string $mobile Mobile phone number
     *
     * @return $this
     */
    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * @return string Phone number
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone Phone number
     *
     * @return $this
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string Salutation e.g. 'Mr.'
     */
    public function getSalutation(): string
    {
        return $this->salutation;
    }

    /**
     * @param string $salutation Salutation e.g. 'Mr.'
     *
     * @return $this
     */
    public function setSalutation(string $salutation): self
    {
        $this->salutation = $salutation;

        return $this;
    }

    /**
     * @return string State
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state State
     *
     * @return $this
     */
    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string Street + streetnumber
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param string $street Street + streetnumber
     *
     * @return $this
     */
    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return string Title e.g. ("Prof. Dr.")
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title Title e.g. ("Prof. Dr.")
     *
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string Zip / postal code
     */
    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode Zip / postal code
     *
     * @return $this
     */
    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }
}
