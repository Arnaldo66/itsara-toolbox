<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UploadFormType;
use App\Entity\Command;
use \Dompdf\Dompdf;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(UploadFormType::class, null);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = fopen($_FILES['upload_form']['tmp_name']['export'], "r");
            $this->insertCSVInDB($file);
            return $this->redirectToRoute('listing');
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/listing", name="listing")
     */
    public function listing(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $commands = $em->getRepository('App\Entity\Command')->findBy(
            array('isDiscount'=> false), array('number' => 'ASC')
        );

        return $this->render('home/listing.html.twig', [
            'commands' => $commands
        ]);
    }

    /**
     * @Route("/print", name="print")
     */
    public function print(Request $request)
    {
        $this->updateCommand($_POST);
        return $this->redirectToRoute('render_pdf');
    }

    /**
     * @Route("/render-pdf", name="render_pdf")
     */
    public function renderPdf(Request $request)
    {
        $commands = $this->createJsonForPDF();
        return $this->render('home/print.html.twig', [
            'commands' => $commands
        ]);
    }

    private function updateCommand(array $post)
    {
        $em = $this->getDoctrine()->getManager();
        $commandRepo = $em->getRepository('App\Entity\Command');
        $allCommands = $commandRepo->findAll();
        $arrayIds = [];
        foreach ($post as $key => $value) {
            $arrayIds[] = explode('_', $key)[1];
        }

        foreach ($allCommands as $command) {
            if(!$command->isDiscount()){
                $command->setPrint(false);
            }
            if(in_array($command->getId(), $arrayIds)){
                $command->setPrint(true);
            }
        }
        $em->flush();
    }

    private function createJsonForPDF()
    {
        $em = $this->getDoctrine()->getManager();
        $commands = $em->getRepository('App\Entity\Command')->getUniqueCommandNumber();

        $arrayCommand = [];
        $arrayCommand['commands'] = [];
        foreach ($commands as $value) {
            dump($value);
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
            $arrayDetail['dateCommand'] = $value['dateCommand'];
            $arrayDetail['typePayment'] = $value['typePayment'];
            $arrayDetail['discounts'] = [];

            $discounts = $em->getRepository('App\Entity\Command')->findby(array(
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

            $articles = $em->getRepository('App\Entity\Command')->findby(array(
                'number' => $value['number'],
                'isDiscount' => false
            ));

            $sousTotal = 0;
            $totalTVAHorsPort = 0;
            foreach ($articles as $article) {
                $arrayArticle = [];
                $arrayArticle['article'] = $article->getArticle();
                $arrayArticle['nbArticle'] = $article->getNbArticle();
                $arrayArticle['prixUnitaire'] = $article->getUnitPriceTTC();
                $arrayArticle['TVA'] = 20;
                $arrayArticle['priceTTC'] = $article->getPriceTTC();

                $sousTotal = $sousTotal + floatval(str_replace(',', '.', $arrayArticle['priceTTC']));

                $arrayDetail['articles'][] = $arrayArticle;
            }


            $arrayDetail['sousTotal'] = $sousTotal;
            $arrayDetail['totalTVAHorsPort'] = round(($sousTotal * 20) / 100, 2);
            $arrayDetail['fraisPortHT'] = $value['shippingCoastHT'];
            $arrayDetail['fraisPortTTC'] = $value['shippingCoastTTC'];
            $arrayDetail['totalTVA'] = $arrayDetail['totalTVAHorsPort'] + floatval(str_replace(',', '.', $arrayDetail['fraisPortTTC'])) - floatval(str_replace(',', '.', $arrayDetail['fraisPortHT']));
            $arrayDetail['totalRemise'] = $totalRemise;

            $arrayDetail['total'] = $arrayDetail['sousTotal'] + floatval(str_replace(',', '.', $arrayDetail['fraisPortTTC'])) + ($totalRemise);

            $arrayCommand['commands'][] = $arrayDetail;

        }

        return json_encode($arrayCommand);
    }

    private function insertCSVInDB($file)
    {
        $this->truncateCommand();
        $row = 1;
        while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
            if($row > 1){
                $this->createCommand($data);
            }
            $row++;
        }

    }

    private function truncateCommand()
    {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $platform   = $connection->getDatabasePlatform();

        $connection->executeUpdate($platform->getTruncateTableSQL('command', true));
    }

    private function createCommand(array $data)
    {


        $command = new Command;
        $command->setNumber($data[0]);
        $command->setDateCommand($data[2]);
        $command->setTypePayment($data[3]);
        $command->setArticle($data[4]);
        $command->setUnitPriceTTC($data[6]);
        $command->setUnitPriceHT($data[7]);
        $command->setShippingCoastHT($data[8]);
        $command->setShippingCoastTTC($data[9]);
        $command->setTVA($data[11]);
        $command->setNbArticle($data[12]);
        $command->setPriceTTC($data[13]);
        $command->setPriceHT($data[14]);
        $command->setName($data[19]);
        $command->setFirstName($data[21]);
        $command->setStreetComplement($data[22]);
        $command->setStreet($data[23]);
        $command->setPostalCode($data[24]);
        $command->setCity($data[25]);
        $command->setProvince($data[26]);
        $command->setCountry($data[27]);
        $command->setPhone($data[28]);
        $command->setEmail($data[29]);
        $command->setPrint($data[29]);
        $command->setIsDiscount(false);

        if(strpos($data[4], 'Code de réduction') !== false){
            $command->setIsDiscount(true);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($command);
        $em->flush();
    }
}
