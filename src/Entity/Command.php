<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandRepository")
 */
class Command
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dateCommand;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typePayment;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $article;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $unitPriceTTC;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $unitPriceHT;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $shippingCoastHT;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $shippingCoastTTC;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $streetComplement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $province;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $priceTTC;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $priceHT;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $TVA;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nbArticle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $print;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isDiscount;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $declinaison;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $infoBill;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $infoDelivery;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nameShipping;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstNameShipping;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $streetShipping;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $postalCodeShipping;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cityShipping;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $provinceShipping;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $countryShipping;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phoneShipping;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $emailShipping;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $streetComplementShipping;

    public function getId()
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getdateCommand(): ?string
    {
        return $this->dateCommand;
    }

    public function setdateCommand(?string $dateCommand): self
    {
        $this->dateCommand = $dateCommand;

        return $this;
    }

    public function getTypePayment(): ?string
    {
        return $this->typePayment;
    }

    public function setTypePayment(?string $typePayment): self
    {
        $this->typePayment = $typePayment;

        return $this;
    }

    public function getArticle(): ?string
    {
        return $this->article;
    }

    public function setArticle(?string $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getUnitPriceTTC(): ?string
    {
        return $this->unitPriceTTC;
    }

    public function setUnitPriceTTC(?string $unitPriceTTC): self
    {
        $this->unitPriceTTC = $unitPriceTTC;

        return $this;
    }

    public function getUnitPriceHT(): ?string
    {
        return $this->unitPriceHT;
    }

    public function setUnitPriceHT(?string $unitPriceHT): self
    {
        $this->unitPriceHT = $unitPriceHT;

        return $this;
    }

    public function getShippingCoastHT(): ?string
    {
        return $this->shippingCoastHT;
    }

    public function setShippingCoastHT(?string $shippingCoastHT): self
    {
        $this->shippingCoastHT = $shippingCoastHT;

        return $this;
    }

    public function getShippingCoastTTC(): ?string
    {
        return $this->shippingCoastTTC;
    }

    public function setShippingCoastTTC(?string $shippingCoastTTC): self
    {
        $this->shippingCoastTTC = $shippingCoastTTC;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getStreetComplement(): ?string
    {
        return $this->streetComplement;
    }

    public function setStreetComplement(?string $streetComplement): self
    {
        $this->streetComplement = $streetComplement;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(?string $province): self
    {
        $this->province = $province;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPriceTTC(): ?string
    {
        return $this->priceTTC;
    }

    public function setPriceTTC(?string $priceTTC): self
    {
        $this->priceTTC = $priceTTC;

        return $this;
    }

    public function getPriceHT(): ?string
    {
        return $this->priceHT;
    }

    public function setPriceHT(?string $priceHT): self
    {
        $this->priceHT = $priceHT;

        return $this;
    }

    public function getTVA(): ?string
    {
        return $this->TVA;
    }

    public function setTVA(?string $TVA): self
    {
        $this->TVA = $TVA;

        return $this;
    }

    public function getNbArticle(): ?string
    {
        return $this->nbArticle;
    }

    public function setNbArticle(?string $nbArticle): self
    {
        $this->nbArticle = $nbArticle;

        return $this;
    }

    public function getPrint(): ?bool
    {
        return $this->print;
    }

    public function setPrint(bool $print): self
    {
        $this->print = $print;

        return $this;
    }

    public function getIsDiscount(): ?bool
    {
        return $this->isDiscount;
    }

    public function setIsDiscount(?bool $isDiscount): self
    {
        $this->isDiscount = $isDiscount;

        return $this;
    }

    public function getDeclinaison(): ?string
    {
        return $this->declinaison;
    }

    public function setDeclinaison(?string $declinaison): self
    {
        $this->declinaison = $declinaison;

        return $this;
    }

    public function getInfoBill(): ?string
    {
        return $this->infoBill;
    }

    public function setInfoBill(?string $infoBill): self
    {
        $this->infoBill = $infoBill;

        return $this;
    }

    public function getInfoDelivery(): ?string
    {
        return $this->infoDelivery;
    }

    public function setInfoDelivery(?string $infoDelivery): self
    {
        $this->infoDelivery = $infoDelivery;

        return $this;
    }

    public function getNameShipping(): ?string
    {
        return $this->nameShipping;
    }

    public function setNameShipping(?string $nameShipping): self
    {
        $this->nameShipping = $nameShipping;

        return $this;
    }

    public function getFirstNameShipping(): ?string
    {
        return $this->firstNameShipping;
    }

    public function setFirstNameShipping(?string $firstNameShipping): self
    {
        $this->firstNameShipping = $firstNameShipping;

        return $this;
    }

    public function getStreetShipping(): ?string
    {
        return $this->streetShipping;
    }

    public function setStreetShipping(?string $streetShipping): self
    {
        $this->streetShipping = $streetShipping;

        return $this;
    }

    public function getPostalCodeShipping(): ?string
    {
        return $this->postalCodeShipping;
    }

    public function setPostalCodeShipping(?string $postalCodeShipping): self
    {
        $this->postalCodeShipping = $postalCodeShipping;

        return $this;
    }

    public function getCityShipping(): ?string
    {
        return $this->cityShipping;
    }

    public function setCityShipping(?string $cityShipping): self
    {
        $this->cityShipping = $cityShipping;

        return $this;
    }

    public function getProvinceShipping(): ?string
    {
        return $this->provinceShipping;
    }

    public function setProvinceShipping(?string $provinceShipping): self
    {
        $this->provinceShipping = $provinceShipping;

        return $this;
    }

    public function getCountryShipping(): ?string
    {
        return $this->countryShipping;
    }

    public function setCountryShipping(?string $countryShipping): self
    {
        $this->countryShipping = $countryShipping;

        return $this;
    }

    public function getPhoneShipping(): ?string
    {
        return $this->phoneShipping;
    }

    public function setPhoneShipping(?string $phoneShipping): self
    {
        $this->phoneShipping = $phoneShipping;

        return $this;
    }

    public function getEmailShipping(): ?string
    {
        return $this->emailShipping;
    }

    public function setEmailShipping(?string $emailShipping): self
    {
        $this->emailShipping = $emailShipping;

        return $this;
    }

    public function getStreetComplementShipping(): ?string
    {
        return $this->streetComplementShipping;
    }

    public function setStreetComplementShipping(?string $streetComplementShipping): self
    {
        $this->streetComplementShipping = $streetComplementShipping;

        return $this;
    }
}
