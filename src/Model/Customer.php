<?php

declare(strict_types=1);

namespace Jtl\Connector\Core\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Customer address data and preference properties.
 *
 * @access     public
 * @package    Jtl\Connector\Core\Model
 * @subpackage Product
 */
#[Serializer\AccessType(['value' => 'public_method'])]
class Customer extends AbstractI18n implements IdentityInterface
{
    /** @var Identity References a customer group */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('customerGroupId')]
    #[Serializer\Accessor(getter: 'getCustomerGroupId', setter: 'setCustomerGroupId')]
    protected Identity $customerGroupId;

    /** @var Identity Unique customer id */
    #[Serializer\Type(Identity::class)]
    #[Serializer\SerializedName('id')]
    #[Serializer\Accessor(getter: 'getId', setter: 'setId')]
    protected Identity $id;

    #[Serializer\Type('double')]
    #[Serializer\SerializedName('accountCredit')]
    #[Serializer\Accessor(getter: 'getAccountCredit', setter: 'setAccountCredit')]
    protected float $accountCredit = 0.0;

    #[Serializer\Type(\DateTimeInterface::class)]
    #[Serializer\SerializedName('birthday')]
    #[Serializer\Accessor(getter: 'getBirthday', setter: 'setBirthday')]
    protected ?\DateTimeInterface $birthday = null;

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

    #[Serializer\Type(\DateTimeInterface::class)]
    #[Serializer\SerializedName('creationDate')]
    #[Serializer\Accessor(getter: 'getCreationDate', setter: 'setCreationDate')]
    protected ?\DateTimeInterface $creationDate = null;

    /** @var string Optional customer number set by JTL-Wawi ERP software */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('customerNumber')]
    #[Serializer\Accessor(getter: 'getCustomerNumber', setter: 'setCustomerNumber')]
    protected string $customerNumber = '';

    /** @var string Delivery instruction e.g. "c/o John Doe" */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('deliveryInstruction')]
    #[Serializer\Accessor(getter: 'getDeliveryInstruction', setter: 'setDeliveryInstruction')]
    protected string $deliveryInstruction = '';

    /** @var double Percentage discount for customer on all prices */
    #[Serializer\Type('double')]
    #[Serializer\SerializedName('discount')]
    #[Serializer\Accessor(getter: 'getDiscount', setter: 'setDiscount')]
    protected float $discount = 0.0;

    /** @var string E-Mail address */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('eMail')]
    #[Serializer\Accessor(getter: 'getEMail', setter: 'setEMail')]
    protected string $eMail = '';

    /** @var string Extra address line e.g. "Apartment 2.5" */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('extraAddressLine')]
    #[Serializer\Accessor(getter: 'getExtraAddressLine', setter: 'setExtraAddressLine')]
    protected string $extraAddressLine = '';

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('fax')]
    #[Serializer\Accessor(getter: 'getFax', setter: 'setFax')]
    protected string $fax = '';

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('firstName')]
    #[Serializer\Accessor(getter: 'getFirstName', setter: 'setFirstName')]
    protected string $firstName = '';

    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('hasCustomerAccount')]
    #[Serializer\Accessor(getter: 'getHasCustomerAccount', setter: 'setHasCustomerAccount')]
    protected bool $hasCustomerAccount = false;

    /** @var bool Optional flag if customer receives newsletter. If true, customer wants to receive newsletter. */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('hasNewsletterSubscription')]
    #[Serializer\Accessor(getter: 'getHasNewsletterSubscription', setter: 'setHasNewsletterSubscription')]
    protected bool $hasNewsletterSubscription = false;

    /**
     * @var bool Flag if customer is active (login allowed).
     *              True, if customer is allowed to login with his E-Mail address and password.
     */
    #[Serializer\Type('boolean')]
    #[Serializer\SerializedName('isActive')]
    #[Serializer\Accessor(getter: 'getIsActive', setter: 'setIsActive')]
    protected bool $isActive = false;

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('lastName')]
    #[Serializer\Accessor(getter: 'getLastName', setter: 'setLastName')]
    protected string $lastName = '';

    /** @var string Mobile phone number */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('mobile')]
    #[Serializer\Accessor(getter: 'getMobile', setter: 'setMobile')]
    protected string $mobile = '';

    /** @var string customer note */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('note')]
    #[Serializer\Accessor(getter: 'getNote', setter: 'setNote')]
    protected string $note = '';

    /** @var string Customer origin */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('origin')]
    #[Serializer\Accessor(getter: 'getOrigin', setter: 'setOrigin')]
    protected string $origin = '';

    /** @var string Phone number */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('phone')]
    #[Serializer\Accessor(getter: 'getPhone', setter: 'setPhone')]
    protected string $phone = '';

    /** @var string Salutation (german: "Anrede") */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('salutation')]
    #[Serializer\Accessor(getter: 'getSalutation', setter: 'setSalutation')]
    protected string $salutation = '';

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('state')]
    #[Serializer\Accessor(getter: 'getState', setter: 'setState')]
    protected string $state = '';

    /** @var string Street name and number */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('street')]
    #[Serializer\Accessor(getter: 'getStreet', setter: 'setStreet')]
    protected string $street = '';

    /** @var string Title, e.g. "Prof. Dr." */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('title')]
    #[Serializer\Accessor(getter: 'getTitle', setter: 'setTitle')]
    protected string $title = '';

    /** @var string VAT number (german "USt-ID") */
    #[Serializer\Type('string')]
    #[Serializer\SerializedName('vatNumber')]
    #[Serializer\Accessor(getter: 'getVatNumber', setter: 'setVatNumber')]
    protected string $vatNumber = '';

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('websiteUrl')]
    #[Serializer\Accessor(getter: 'getWebsiteUrl', setter: 'setWebsiteUrl')]
    protected string $websiteUrl = '';

    #[Serializer\Type('string')]
    #[Serializer\SerializedName('zipCode')]
    #[Serializer\Accessor(getter: 'getZipCode', setter: 'setZipCode')]
    protected string $zipCode = '';

    /** @var KeyValueAttribute[] */
    #[Serializer\Type('array<Jtl\Connector\Core\Model\KeyValueAttribute>')]
    #[Serializer\SerializedName('attributes')]
    #[Serializer\AccessType(['value' => 'reflection'])]
    protected array $attributes = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->customerGroupId = new Identity();
        $this->id              = new Identity();
    }

    /**
     * @return Identity References a customer group
     */
    public function getCustomerGroupId(): Identity
    {
        return $this->customerGroupId;
    }

    /**
     * @param Identity $customerGroupId References a customer group
     *
     * @return $this
     */
    public function setCustomerGroupId(Identity $customerGroupId): self
    {
        $this->customerGroupId = $customerGroupId;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getId(): Identity
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function setId(Identity $identity): self
    {
        $this->id = $identity;

        return $this;
    }

    /**
     * @return float
     */
    public function getAccountCredit(): float
    {
        return $this->accountCredit;
    }

    /**
     * @param float $accountCredit
     *
     * @return $this
     */
    public function setAccountCredit(float $accountCredit): self
    {
        $this->accountCredit = $accountCredit;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null Date of birth
     */
    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    /**
     * @param \DateTimeInterface|null $birthday Date of birth
     *
     * @return $this
     */
    public function setBirthday(?\DateTimeInterface $birthday = null): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @return string City
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city City
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
     * @return \DateTimeInterface|null
     */
    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    /**
     * @param \DateTimeInterface|null $creationDate
     *
     * @return $this
     */
    public function setCreationDate(?\DateTimeInterface $creationDate = null): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * @return string Optional customer number set by JTL-Wawi ERP software
     */
    public function getCustomerNumber(): string
    {
        return $this->customerNumber;
    }

    /**
     * @param string $customerNumber Optional customer number set by JTL-Wawi ERP software
     *
     * @return $this
     */
    public function setCustomerNumber(string $customerNumber): self
    {
        $this->setIdentificationStringBySubject('customerNumber', \sprintf('Customer number = %s', $customerNumber));
        $this->customerNumber = $customerNumber;

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
     * @return float Percentage discount for customer on all prices
     */
    public function getDiscount(): float
    {
        return $this->discount;
    }

    /**
     * @param float $discount Percentage discount for customer on all prices
     *
     * @return $this
     */
    public function setDiscount(float $discount): self
    {
        $this->discount = $discount;

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
     * @return string Extra address line e.g. "Apartment 2.5"
     */
    public function getExtraAddressLine(): string
    {
        return $this->extraAddressLine;
    }

    /**
     * @param string $extraAddressLine Extra address line e.g. "Apartment 2.5"
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
     * @return bool
     */
    public function getHasCustomerAccount(): bool
    {
        return $this->hasCustomerAccount;
    }

    /**
     * @param bool $hasCustomerAccount
     *
     * @return $this
     */
    public function setHasCustomerAccount(bool $hasCustomerAccount): self
    {
        $this->hasCustomerAccount = $hasCustomerAccount;

        return $this;
    }

    /**
     * @return bool Optional flag if customer receives newsletter. If true, customer wants to receive newsletter.
     */
    public function getHasNewsletterSubscription(): bool
    {
        return $this->hasNewsletterSubscription;
    }

    /**
     * @param bool $hasNewsletterSubscription Optional flag if customer receives newsletter.
     *                                        If true, customer wants to receive newsletter.
     *
     * @return $this
     */
    public function setHasNewsletterSubscription(bool $hasNewsletterSubscription): self
    {
        $this->hasNewsletterSubscription = $hasNewsletterSubscription;

        return $this;
    }

    /**
     * @return bool Flag if customer is active (login allowed).
     *                  True, if customer is allowed to login with his E-Mail address and password.
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive Flag if customer is active (login allowed).
     *                       True, if customer is allowed to login with his E-Mail address and password.
     *
     * @return $this
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

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
     * @return string customer note
     */
    public function getNote(): string
    {
        return $this->note;
    }

    /**
     * @param string $note customer note
     *
     * @return $this
     */
    public function setNote(string $note): self
    {
        $this->note = $note;

        return $this;
    }

    /**
     * @return string Customer origin
     */
    public function getOrigin(): string
    {
        return $this->origin;
    }

    /**
     * @param string $origin Customer origin
     *
     * @return $this
     */
    public function setOrigin(string $origin): self
    {
        $this->origin = $origin;

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
     * @return string Salutation (german: "Anrede")
     */
    public function getSalutation(): string
    {
        return $this->salutation;
    }

    /**
     * @param string $salutation Salutation (german: "Anrede")
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
     * @return string Street name
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param string $street Street name
     *
     * @return $this
     */
    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return string Title, e.g. "Prof. Dr."
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title Title, e.g. "Prof. Dr."
     *
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string VAT number (german "USt-ID")
     */
    public function getVatNumber(): string
    {
        return $this->vatNumber;
    }

    /**
     * @param string $vatNumber VAT number (german "USt-ID")
     *
     * @return $this
     */
    public function setVatNumber(string $vatNumber): self
    {
        $this->vatNumber = $vatNumber;

        return $this;
    }

    /**
     * @return string WWW address
     */
    public function getWebsiteUrl(): string
    {
        return $this->websiteUrl;
    }

    /**
     * @param string $websiteUrl WWW address
     *
     * @return $this
     */
    public function setWebsiteUrl(string $websiteUrl): self
    {
        $this->websiteUrl = $websiteUrl;

        return $this;
    }

    /**
     * @return string ZIP / postal code
     */
    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode ZIP / postal code
     *
     * @return $this
     */
    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * @param KeyValueAttribute $attribute
     *
     * @return $this
     */
    public function addAttribute(KeyValueAttribute $attribute): self
    {
        $this->attributes[] = $attribute;

        return $this;
    }

    /**
     * @return KeyValueAttribute[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param KeyValueAttribute ...$attributes
     *
     * @return $this
     */
    public function setAttributes(KeyValueAttribute ...$attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearAttributes(): self
    {
        $this->attributes = [];

        return $this;
    }
}
