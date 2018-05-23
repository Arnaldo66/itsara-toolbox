<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UploadFormType;
use App\Services\CommandService;
use App\Services\PDFService;
use \Dompdf\Dompdf;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, CommandService $commandeService)
    {
        $form = $this->createForm(UploadFormType::class, null);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = fopen($_FILES['upload_form']['tmp_name']['export'], "r");
            $commandeService->insertCSVInDB($file);
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
    public function print(CommandService $commandeService)
    {
        $commandeService->updateCommand($_POST);
        return $this->redirectToRoute('render_pdf');
    }

    /**
     * @Route("/print-vignette", name="print_vignette")
     */
    public function printVignette(PDFService $pdfService)
    {
        $commands = $pdfService->getCommandVignette();
        return $this->render('home/print_vignette.html.twig', [
            'commands' => $commands
        ]);
    }

    /**
     * @Route("/render-pdf", name="render_pdf")
     */
    public function renderPdf(Request $request, PDFService $pdfService)
    {
        $commands = $pdfService->createJsonForPDF();
        return $this->render('home/print.html.twig', [
            'commands' => $commands
        ]);
    }
}
