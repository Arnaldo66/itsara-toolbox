<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;

class PDFService
{
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }


    /**
     * get command Adresse
     */
     public function getCommandVignette()
     {
        $commands = $this->em->getRepository('App\Entity\Command')->getUniqueCommandNumber();
        $arrayAdresse = [];
        foreach ($commands as $value) {
            $arrayDetail = [];
            if(strlen($value['postalCodeShipping'])){
                $arrayDetail['name'] = $value['nameShipping'];
                $arrayDetail['firstName'] = $value['firstNameShipping'];
                $arrayDetail['streetComplement'] = $value['streetComplementShipping'];
                $arrayDetail['street'] = $value['streetShipping'];
                $arrayDetail['postalCode'] = $value['postalCodeShipping'];
                $arrayDetail['province'] = $value['provinceShipping'];
                $arrayDetail['city'] = $value['cityShipping'];
                $arrayDetail['country'] = $value['countryShipping'];
            }else{
                $arrayDetail['name'] = $value['name'];
                $arrayDetail['firstName'] = $value['firstName'];
                $arrayDetail['street'] = $value['street'];
                $arrayDetail['streetComplement'] = $value['streetComplement'];
                $arrayDetail['postalCode'] = $value['postalCode'];
                $arrayDetail['province'] = $value['province'];
                $arrayDetail['city'] = $value['city'];
                $arrayDetail['country'] = $value['country'];
            }

            $arrayAdresse[] = $arrayDetail;
        }
        return json_encode($arrayAdresse);
     }

    /**
     * Create a json with pdf detail
     */
    public function createJsonForPDF(): string
    {
        $commands = $this->em->getRepository('App\Entity\Command')->getUniqueCommandNumber();
        $arrayCommand = [];
        foreach ($commands as $value) {
            $arrayDetail = [];
            $arrayDetail['number'] = $value['number'];
            $arrayDetail['name'] = $value['name'];
            $arrayDetail['firstName'] = $value['firstName'];
            $arrayDetail['street'] = $value['street'];
            $arrayDetail['streetComplement'] = $value['streetComplement'];
            $arrayDetail['postalCode'] = $value['postalCode'];
            $arrayDetail['province'] = $value['province'];
            $arrayDetail['city'] = $value['city'];
            $arrayDetail['country'] = $value['country'];
            $arrayDetail['phone'] = $value['phone'];
            $arrayDetail['email'] = $value['email'];
            $arrayDetail['firstNameShipping'] = $value['firstNameShipping'];
            $arrayDetail['streetComplementShipping'] = $value['streetComplementShipping'];
            $arrayDetail['streetShipping'] = $value['streetShipping'];
            $arrayDetail['postalCodeShipping'] = $value['postalCodeShipping'];
            $arrayDetail['provinceShipping'] = $value['provinceShipping'];
            $arrayDetail['cityShipping'] = $value['cityShipping'];
            $arrayDetail['countryShipping'] = $value['countryShipping'];
            $arrayDetail['phoneShipping'] = $value['phoneShipping'];
            $arrayDetail['emailShipping'] = $value['emailShipping'];
            $arrayDetail['infoBill'] = $value['infoBill'];
            $arrayDetail['infoDelivery'] = $value['infoDelivery'];
            $arrayDetail['dateCommand'] = $value['dateCommand'];
            $arrayDetail['typePayment'] = $value['typePayment'];
            $arrayDetail['discounts'] = [];

            $discounts = $this->em->getRepository('App\Entity\Command')->findby(array(
                'number' => $value['number'],
                'isDiscount' => true
            ));

            $totalRemise = 0;
            foreach ($discounts as  $discount) {
                $arrayDiscount = [];
                $arrayDiscount['discountCode'] = $discount->getArticle();
                $arrayDiscount['discountAmount'] = $discount->getUnitPriceTTC();

                $totalRemise = $totalRemise + (floatval(str_replace(',', '.', $arrayDiscount['discountAmount'])));

                $arrayDetail['discounts'][] = $arrayDiscount;
            }

            $arrayDetail['articles'] = [];

            $articles = $this->em->getRepository('App\Entity\Command')->findby(array(
                'number' => $value['number'],
                'isDiscount' => false
            ));

            $sousTotal = 0;
            $totalTVAHorsPort = 0;
            foreach ($articles as $article) {
                $arrayArticle = [];
                $arrayArticle['article'] = $article->getArticle();
                $arrayArticle['declinaison'] = $article->getDeclinaison();
                $arrayArticle['nbArticle'] = $article->getNbArticle();
                $arrayArticle['prixUnitaire'] = $article->getUnitPriceTTC();
                $arrayArticle['TVA'] = '20%';
                $arrayArticle['priceTTC'] = $article->getPriceTTC();

                $sousTotal = $sousTotal + $this->stringToFloat($arrayArticle['priceTTC']);
                $arrayDetail['articles'][] = $arrayArticle;
            }
            $totalTVAHorsPort = $this->tvaCalculate($sousTotal, $totalRemise);

            $arrayDetail['sousTotal'] = $sousTotal;
            $arrayDetail['totalTVAHorsPort'] = round($totalTVAHorsPort,2);
            $arrayDetail['fraisPortHT'] = $value['shippingCoastHT'];
            $arrayDetail['fraisPortTTC'] = $value['shippingCoastTTC'];
            $arrayDetail['totalTVA'] = round($totalTVAHorsPort + ($this->stringToFloat($arrayDetail['fraisPortTTC'])) - $this->stringToFloat($arrayDetail['fraisPortHT']),2);
            $arrayDetail['totalRemise'] = $totalRemise;

            $arrayDetail['total'] = round($arrayDetail['sousTotal'] + $this->stringToFloat($arrayDetail['fraisPortTTC']) + ($totalRemise),2);

            $arrayCommand[] = $arrayDetail;

        }

        return json_encode($arrayCommand);
    }

    private function stringToFloat($value)
    {
        return floatval(str_replace(',', '.', $value));
    }

    private function tvaCalculate($sousTotal, $totalRemise)
    {
        $calculHorsTaxe = $sousTotal - abs($totalRemise);
        return $calculHorsTaxe - ($calculHorsTaxe / 1.2);
    }
}
