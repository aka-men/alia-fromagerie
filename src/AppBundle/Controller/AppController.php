<?php

/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 08/04/2017
 * Time: 11:27
 */
namespace AppBundle\Controller;
use AppBundle\Entity\Facture;
use AppBundle\Form\ConfigEntrepriseType;
use AppBundle\Manager\ConfigurationManager;
use Ensepar\Html2pdfBundle\Factory\Html2pdfFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\DateTime;

class AppController extends Controller
{

    /**
     * @Route("/", name="app_index")
     */
    public function indexAction(Request $request) {

        /*$commandes = $this->get('app.commande.manager')->findBy([],[],500,500);
        $count = $this->get('app.commande.manager')->count(array());

        echo count($commandes).' Commandes trouvées<br/>';
        echo '<br/>Traitement ...<br/>';
        foreach ($commandes as $cmd){
            if($cmd->getReste() <= 0)
                $cmd->setIsPaid(true);
            else
                $cmd->setIsPaid(false);
            echo 'Commande N° '.$cmd->getId().' isPaid = '.($cmd->getIsPaid() ? 1 : 0).'<br/>';
        }
        $this->get('app.commande.manager')->flush();
        echo '<br/>Fin Traitement<br/>';die;*/

        /*$factures = $this->get('app.facture.manager')->findBy([],[],500,0);
        echo count($factures).' Factures trouvées<br/>';
        echo '<br/>Traitement ...<br/>';
        /** @var Facture $fct */
        /*foreach ($factures as $fct){
            $cmd = $fct->getCommande();
            $fct->setDate($cmd->getDateLivraison());
            $fct->addCommande($cmd);
            $cmd->setFactureGlobal($fct);
            $fct->setClient($cmd->getClient());
            $fct->setEntreprise($cmd->getEntreprise());
            echo 'Commande N° '.$cmd->getId().' facture = '.$fct->numero().'<br/>';
        }
        $this->get('app.facture.manager')->flush();
        echo '<br/>Fin Traitement<br/>';die;*/

        $totalAchatsInLast12Month = $this->get('app.achat.manager')->getTotalByMonthsInLastNbrMonth(12);
        $totalChargesInLast12Month = $this->get('app.charge.manager')->getTotalByMonthsInLastNbrMonth(12,'autre');
        $totalChargesEmployesInLast12Month = $this->get('app.charge.manager')->getTotalByMonthsInLastNbrMonth(12,'employe');
        $totalCommandesInLast12Month = $this->get('app.commande.manager')->getTotalByMonthsInLastNbrMonth(12);
        $paiementsVentes = $this->get('app.paiement.manager')->reglementDeposer(array('Chèque','Effet'),3);
        $paiementsCharges = $this->get('app.charge.manager')->getReglements(array('Chèque','Effet'),3);
        $paiementsAchats = $this->get('app.achat.manager')->getReglements(array('Chèque','Effet'),3);
        $date = new \DateTime();
        $totalAchat = $this->get('app.achat.manager')->total(array(
            "annee" => $date->format('Y'),
            "mois" => $date->format('m')
        ));
        $totalCharges = $this->get('app.charge.manager')->total(array(
            "annee" => $date->format('Y'),
            "mois" => $date->format('m')
        ),'autre');
        $totalChargesEmployes = $this->get('app.charge.manager')->total(array(
            "annee" => $date->format('Y'),
            "mois" => $date->format('m')
        ),'employe');
        $totalCommandes = $this->get('app.commande.manager')->total(array(
            "annee" => $date->format('Y'),
            "mois" => $date->format('m')
        ));
        $totalCommandeImpayes = $this->get('app.commande.manager')->total(array(
            "annee" => $date->format('Y'),
            "mois" => $date->format('m'),
            "isPaid" => false
        ));
        $totalPaiementsInvalides = $this->get('app.paiement.manager')->totalInvalide();

        return $this->render("AppBundle::index.html.twig",array(
            'paiementsVentes' => $paiementsVentes['paiements'],
            'paiementsCharges' => $paiementsCharges,
            'paiementsAchats' => $paiementsAchats,
            'totalAchats' => $totalAchat,
            'totalCharges' => $totalCharges,
            'totalChargesEmployes' => $totalChargesEmployes,
            'totalCommandes' => $totalCommandes,
            'totalCommandeImpayes' => $totalCommandeImpayes,
            "totalAchatsInLast12Month" => $totalAchatsInLast12Month,
            "totalChargesInLast12Month" => $totalChargesInLast12Month,
            "totalCommandesInLast12Month" => $totalCommandesInLast12Month,
            "totalChargesEmployesInLast12Month" => $totalChargesEmployesInLast12Month,
            "totalPaiementsInvalides" => $totalPaiementsInvalides
        ));
    }

    /**
     * @Route("/prod-vente-achat", name="app_prod_vente_achat")
     */
    public function prodVenteAchatAction(Request $request) {
        if($request->isXmlHttpRequest() and $request->isMethod('get')){
            $date = new \DateTime();
            $mois = $request->query->get('month',$date->format('m'));
            $annee = $request->query->get('year',$date->format('Y'));
            $ventes = $this->get('app.produit.manager')->quantiteVendus($mois,$annee);
            $achats = $this->get('app.produit.manager')->quantiteAchetes($mois,$annee);
            $production = $this->get('app.produit.manager')->quantiteProduite($mois,$annee);
            return new JsonResponse(array(
                "code" => 1,
                "ventes" => $ventes,
                "achats" => $achats,
                "production" => $production,
                "mois" => $this->get('translator')->trans($date->setDate($annee,$mois,1)->format("F")),
                "annee" => $annee
            ));
        }
        throw $this->createNotFoundException('L\'URL demandée n\'existe pas !!');
    }

    /**
     * @Route("settings/general", name="app_config")
     */
    public function configAction(Request $request) {
        $formEntreprise = $this->createForm(ConfigEntrepriseType::class);
        if($request->isXmlHttpRequest() and $request->isMethod('POST') and $request->request->has('config_entreprise')){
            $formEntreprise->handleRequest($request);
            if($formEntreprise->isValid()){
                $this->getConfigManager()->setCollection("entreprise",$formEntreprise->getData(),false,true);
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Modifications enregistrées avec succès"
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$formEntreprise->getErrors(true)));
        }
        if($request->isXmlHttpRequest() and $request->isMethod('POST') and $request->request->has('config_admin')){
                $this->getConfigManager()->setCollection("admin",$request->request->get('config_admin',array()),false,true);
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Modifications enregistrées avec succès"
                ));
        }
        if($request->isXmlHttpRequest() and $request->isMethod('POST') and $request->request->has('config_application')){
            $this->getConfigManager()->setCollection("facture",$request->request->get('config_application',array()),true,true);
            if($request->files->count() > 0 and $request->files->has('config_application')){
                foreach ($request->files->get('config_application') as $name => $file){
                    $this->getConfigManager()->setFile($name,$name.'.png',$file);
                }
                $this->getConfigManager()->save();
            }
            return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Modifications enregistrées avec succès"
                ));
         }
        return $this->render("AppBundle:configuration:index.html.twig",array(
            "formEntreprise" => $formEntreprise->createView()
        ));
    }

    /**
     * @Route("/images/{nom}", name="app_images")
     */
    public function imagesAction($nom,Request $request) {
        $image = $this->get('app.image.manager')->findOneBy(array(
            "nom" => $nom
        ));
        if (!$image) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException("L'image demandée n'existe pas");
        }
        $file = $this->getParameter("web_dir") . "images/upload/" . $image->getFullName();
        if (!file_exists($file) or ! is_file($file)) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException("Impossible de trouver le fichier demandée");
        }
        return new \Symfony\Component\HttpFoundation\Response(file_get_contents($file), 200, array("Content-Type" => mime_content_type($file)));
    }

    /**
     * @Route("/reporting", name="app_reporting_index")
     */
    public function reportingAction(Request $request) {
        $mp = $this->get('app.produit.manager')->findBy(array('isMatierePremiere' => true),array());
        return $this->render("AppBundle:reporting:index.html.twig",array(
            'matieres' => $mp
        ));
    }

    /**
     * @Route("/reporting/benefice-net", name="app_reporting_benefice_net")
     */
    public function reportingBeneficeNetAction(Request $request) {
        if($request->isMethod('POST') and (($request->request->has('benefice_month') and count(explode('/',$request->request->get('benefice_month'))) === 2) or ($request->request->has('benefice_date_debut') and $request->request->has('benefice_date_fin')))){
            $criteres = array();
            if($request->request->has('benefice_month') and count(explode('/',$request->request->get('benefice_month'))) === 2){
                $criteres['mois'] = explode('/',$request->request->get('benefice_month'))[0];
                $criteres['annee'] = explode('/',$request->request->get('benefice_month'))[1];
            }elseif ($request->request->has('benefice_date_debut') and $request->request->has('benefice_date_fin')){
                $criteres['date_start'] = $request->request->get('benefice_date_debut');
                $criteres['date_end'] = $request->request->get('benefice_date_fin');
            }
            $reportCommandes = $this->get('app.commande.manager')->report($criteres);
            $reportChargesEmploye = $this->get('app.charge.manager')->report(array_merge($criteres,array('type'=>'employe')));
            $reportChargesAutre = $this->get('app.charge.manager')->report(array_merge($criteres,array('type'=>'autre')));
            $reportAchatsMP = $this->get('app.achat.manager')->report(array_merge($criteres,array('type'=>'MP')));
            $reportAchatsPI = $this->get('app.achat.manager')->report(array_merge($criteres,array('type'=>'PI')));
            if($request->request->has('pdf')){
                $html2pdfFactory = $this->getHtml2PdfFactory();
                $html = $this->renderView('pdf/reporting/benefice_net.html.twig',array(
                    'criteres' => $criteres,
                    'reportCommandes' => $reportCommandes,
                    'reportChargesEmploye' => $reportChargesEmploye,
                    'reportChargesAutre' => $reportChargesAutre,
                    'reportAchatsMP' => $reportAchatsMP,
                    'reportAchatsPI' => $reportAchatsPI
                ));
                $html2pdf = $html2pdfFactory->create('P', 'A4', 'fr', true, 'UTF-8',array('0','0','0','0'));
                $html2pdf->setDefaultFont("CenturyGothic");
                $html2pdf->pdf->SetDisplayMode('real');
                $html2pdf->writeHTML($html);
                return new Response($html2pdf->Output('Rapport_Benefice_Net.pdf'), 200, array('Content-Type' => 'application/pdf'));
            }
            return $this->render('AppBundle:reporting:benefice_net.html.twig',array(
                'criteres' => $criteres,
                'reportCommandes' => $reportCommandes,
                'reportChargesEmploye' => $reportChargesEmploye,
                'reportChargesAutre' => $reportChargesAutre,
                'reportAchatsMP' => $reportAchatsMP,
                'reportAchatsPI' => $reportAchatsPI
            ));
        }
        throw $this->createNotFoundException();
    }



    /**
     * @Route("/reporting/vente", name="app_reporting_vente")
     */
    public function reportingVenteAction(Request $request) {
       if($request->isMethod('POST') and $request->request->has('vente_type') and (($request->request->has('vente_month') and count(explode('/',$request->request->get('vente_month'))) === 2) or ($request->request->has('vente_date_debut') and $request->request->has('vente_date_fin')))){
            $criteres = array();
            if($request->request->has('vente_month') and count(explode('/',$request->request->get('vente_month'))) === 2){
                $criteres['mois'] = explode('/',$request->request->get('vente_month'))[0];
                $criteres['annee'] = explode('/',$request->request->get('vente_month'))[1];
            }elseif ($request->request->has('vente_date_debut') and $request->request->has('vente_date_fin')){
                $criteres['date_start'] = $request->request->get('vente_date_debut');
                $criteres['date_end'] = $request->request->get('vente_date_fin');
            }
            switch ($request->request->get('vente_type')){
                case 'with-invoice':
                    $criteres['has_invoice'] = true;
                    break;
                case 'without-invoice':
                    $criteres['has_invoice'] = false;
                    break;
                case 'unpaid':
                    $criteres['isPaid'] = 0;
                    break;
            }
            $reportCommandes = $this->get('app.commande.manager')->report($criteres);

            if($request->request->has('pdf')){
                $html2pdfFactory = $this->getHtml2PdfFactory();
                $html = $this->renderView('pdf/reporting/vente.html.twig',array(
                    'criteres' => $criteres,
                    'reportCommandes' => $reportCommandes
                ));

                $html2pdf = $html2pdfFactory->create('P', 'A4', 'fr', true, 'UTF-8',array('0','0','0','0'));
                $html2pdf->setDefaultFont("CenturyGothic");
                $html2pdf->pdf->SetDisplayMode('real');
                $html2pdf->writeHTML($html);
                return new Response($html2pdf->Output('Rapport_Ventes.pdf'), 200, array('Content-Type' => 'application/pdf'));
            }
            return $this->render('AppBundle:reporting:vente.html.twig',array(
                'criteres' => $criteres,
                'reportCommandes' => $reportCommandes
            ));
        }
        throw $this->createNotFoundException();
    }




    /**
     * @Route("/reporting/analyses-production", name="app_reporting_analyses_production")
     */
    public function reportingAnalysesProductionAction(Request $request) {
        if($request->request->has('analyses_production_mp') and $request->isMethod('POST') and (($request->request->has('analyses_production_month') and count(explode('/',$request->request->get('analyses_production_month'))) === 2) or ($request->request->has('analyses_production_date_debut') and $request->request->has('analyses_production_date_fin')))){
            $criteres = array();
            $matiere = $this->get('app.produit.manager')->find($request->request->get('analyses_production_mp'));
            if($request->request->has('analyses_production_month') and count(explode('/',$request->request->get('analyses_production_month'))) === 2){
                $criteres['mois'] = explode('/',$request->request->get('analyses_production_month'))[0];
                $criteres['annee'] = explode('/',$request->request->get('analyses_production_month'))[1];
            }elseif ($request->request->has('analyses_production_date_debut') and $request->request->has('analyses_production_date_fin')){
                $criteres['date_start'] = $request->request->get('analyses_production_date_debut');
                $criteres['date_end'] = $request->request->get('analyses_production_date_fin');
            }
            $reportPAMP = $this->get('app.produit_achat.manager')->report(array_merge($criteres,array(
                'type'=>'MP',
                'produit' => $matiere->getId()
            )));
            if($request->request->has('pdf')){
                $html2pdfFactory = $this->getHtml2PdfFactory();
                $html = $this->renderView('pdf/reporting/analyses_production.html.twig',array(
                    'criteres' => $criteres,
                    'reportPAMP' => $reportPAMP,
                    'matiere' => $matiere
                ));
                $html2pdf = $html2pdfFactory->create('L', 'A4', 'fr', true, 'UTF-8',array('0','0','0','0'));
                $html2pdf->setDefaultFont("CenturyGothic");
                $html2pdf->pdf->SetDisplayMode('real');
                $html2pdf->writeHTML($html);
                return new Response($html2pdf->Output('Rapport_Analyses_Production.pdf'), 200, array('Content-Type' => 'application/pdf'));
            }
            return $this->render('AppBundle:reporting:analyses_production.html.twig',array(
                'criteres' => $criteres,
                'reportPAMP' => $reportPAMP,
                'matiere' => $matiere
            ));
        }
        throw $this->createNotFoundException();
    }



    /**
     * @Route("/cv", name="app_cv")
     */
    public function printAction(Request $request) {
        $html2pdfFactory = $this->getHtml2PdfFactory();
        $html = $this->renderView('pdf/cv.html.twig');
        $html2pdf = $html2pdfFactory->create('P', 'A4', 'fr', true, 'UTF-8',array('0','0','0','0'));
        $html2pdf->setDefaultFont("CenturyGothic");
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);
        return new Response($html2pdf->Output('Abdelhak_Ouaddi_CV.pdf'), 200, array('Content-Type' => 'application/pdf'));
    }



    /**
     * @return Html2pdfFactory
     */
    function getHtml2PdfFactory(){
        return $this->get('html2pdf_factory');
    }

    /**
     * @return ConfigurationManager
     */
    function getConfigManager(){
        return $this->get('app.configuration.manager');
    }

}