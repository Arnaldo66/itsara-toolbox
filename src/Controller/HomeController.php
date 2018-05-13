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
            'controller_name' => 'HomeController', 'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/listing", name="listing")
     */
    public function listing(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $commands = $em->getRepository('App\Entity\Command')->findAll();

        return $this->render('home/listing.html.twig', [
            'controller_name' => 'HomeController', 'commands' => $commands
        ]);
    }

    /**
     * @Route("/print", name="print")
     */
    public function print(Request $request)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml('En cours de developpement');

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream();
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

        $em = $this->getDoctrine()->getManager();
        $em->persist($command);
        $em->flush();
    }
}
