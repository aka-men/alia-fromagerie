<?php
/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 09/06/2017
 * Time: 15:23
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Commande;
use AppBundle\Entity\Facture;
use AppBundle\Manager\FactureManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Ensepar\Html2pdfBundle\Factory\Html2pdfFactory;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/factures")
 */
class FactureController extends Controller
{
    /**
     * @Route("", name="app_facture_index")
     */
    public function indexAction(Request $request) {
        if($request->isXmlHttpRequest() and $request->request->has('columns')){
            $param_sort = array(
                '',
                'c.id',
                'f.id',
                'c.date',
                '',
                'c.montantHt',
                'c.tva',
                'c.montantTtc',
                '',
                '',
                '',
            );
            $start = $request->request->get("start");
            $length = $request->request->get("length");
            $criteres = $request->request->get("criteres",array());
            $sort = $param_sort[$request->request->get("order")[0]['column']];
            $dir = $request->request->get("order")[0]['dir'];
            $requestResult = $this->getManager()->listeDataTable($criteres,$sort, $dir, $start, $length);
            $resultat = array(
                "data" => array(),
                'recordsFiltered' => $requestResult["totalFiltred"],
                'recordsTotal' => $requestResult["total"],
                'totals' => $this->renderView('AppBundle:charge:totals.html.twig',array(
                    'totals'=>$requestResult['totals'],
                    'totalGlobal'=>$requestResult['totalGlobal'],
                    'totalAvance'=>$requestResult['totalAvance'],
                    'totalReste' => (float)$requestResult['totalGlobal'] - (float)$requestResult['totalAvance']
                )),
                'produits' => $this->renderView('AppBundle:commande:produits.html.twig',array(
                    'produits'=>$requestResult['produits']
                ))
            );
            $ligne = 1;
            foreach ($requestResult['factures'] as $facture){
                $resultat['data'][] = array(
                    "ligne" => $this->renderView('AppBundle:facture:td.html.twig',array(
                        'facture' => $facture,
                        'td' => 'ligne',
                        "ligne" => $ligne
                    )),
                    "date" => $this->renderView('AppBundle:facture:td.html.twig',array(
                        'facture' => $facture,
                        'td' => 'date'
                    )),
                    "commande" => $this->renderView('AppBundle:facture:td.html.twig',array(
                        'facture' => $facture,
                        'td' => 'commande'
                    )),
                    "client" => $this->renderView('AppBundle:facture:td.html.twig',array(
                        'facture' => $facture,
                        'td' => 'client'
                    )),
                    "id" => $this->renderView('AppBundle:facture:td.html.twig',array(
                        'facture' => $facture,
                        'td' => 'id'
                    )),
                    "commande" => $this->renderView('AppBundle:facture:td.html.twig',array(
                        'facture' => $facture,
                        'td' => 'commande'
                    )),
                    "ht" => $this->renderView('AppBundle:facture:td.html.twig',array(
                        'facture' => $facture,
                        'td' => 'ht'
                    )),
                    "tva" => $this->renderView('AppBundle:facture:td.html.twig',array(
                        'facture' => $facture,
                        'td' => 'tva'
                    )),
                    "ttc" => number_format($facture->getTtc(),2,'.',' '),
                    "frais" => $this->renderView('AppBundle:facture:td.html.twig',array(
                        'facture' => $facture,
                        'td' => 'frais'
                    )),
                    "total" => number_format($facture->getTotal(),2,'.',' '),
                    "avance" => $this->renderView('AppBundle:facture:td.html.twig',array(
                        'facture' => $facture,
                        'td' => 'avance'
                    )),
                    "avanceNum" => $facture->getAvance(),
                    "reste" => number_format($facture->getReste(),2,'.',' '),
                    "action" => $this->renderView('AppBundle:facture:td.html.twig',array(
                        'facture' => $facture,
                        'td' => 'action'
                    )),
                );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        return $this->render("AppBundle:facture:index.html.twig",array(
            "modes" => $this->get('app.mode_payment.manager')->findBy(array(),array("ordre"=>"ASC")),
            "clients" => $this->get('app.client.manager')->findBy(array("isCommercial" => false)),
            "commerciales" => $this->get('app.client.manager')->findBy(array("isCommercial" => true)),
            "entreprises" => $this->get('app.entreprise.manager')->findAll()
        ));
    }

    /**
     * @Route("/new/{id}", name="app_facture_new")
     * @ParamConverter("commande", class="AppBundle:Commande")
     */
    public function newAction(Commande $commande,Request $request) {
        if(!$request->isXmlHttpRequest() or !is_null($commande->getFactureGlobal())) {
            if ((is_null($commande->getTva()) or $commande->getTva() === 0) and !$request->request->has("tva")){
                return new JsonResponse(array(
                    "code" => 0,
                    "title" => "La vente N° ".$commande->getId()." n'a pas de Tva,veuillez l'ajouter Svp",
                    "form" => $this->renderView("AppBundle:commande:tva.form.html.twig",array(
                        "commande" => $commande
                    ))
                ));
            }
            if($request->request->has("tva")){
                $commande->applyTva($request->request->get("tva"));
                $this->get("app.commande.manager")->flush();
            }
            /** @var Facture $facture */
            $facture = $this->getManager()->create();
            $facture->addCommande($commande)->setUser($this->getUser());
            $facture->setClient($commande->getClient());
            $facture->setEntreprise($commande->getEntreprise());
            $commande->setFactureGlobal($facture);
            $this->getManager()->persist($facture,true);
            return new JsonResponse(array(
                "code" => 1,
                "msg" => "La facture N° ".$facture->getId()." est générée avec succès",
                "lien" => $this->generateUrl('app_facture_print',array('id' => $facture->getId()))
            ));
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("collection/new-one", name="app_facture_new_one_collection")
     */
    public function newCollectionAction(Request $request) {
        if($request->isXmlHttpRequest() and $request->request->has("commande") and is_array($request->request->get("commande"))) {
            $commandeWithoutTva = array();
            $commandes = array();
            /** @var Facture $facture */
            $facture = $this->getManager()->create();
            $facture->setUser($this->getUser());
            foreach ($request->request->get("commande") as $cmd){
                /** @var Commande $commande */
                $commande = $this->get('app.commande.manager')->find($cmd['id']);
                if($commande and is_null($commande->getFactureGlobal())){
                    if((is_null($commande->getTva()) or $commande->getTva() === 0) and !array_key_exists("tva",$cmd) )
                        $commandeWithoutTva[] = $commande;
                    else{
                        $commandes[] = $commande;
                        if(is_null($commande->getTva()) or $commande->getTva() === 0)
                            $commande->applyTva((float)$cmd['tva']);
                        $facture->addCommande($commande);
                        $commande->setFactureGlobal($facture);
                    }
                    $facture->setClient($commande->getClient());
                    $facture->setEntreprise($commande->getEntreprise());
                }
            }
            if(count($commandeWithoutTva) > 0){
                return new JsonResponse(array(
                    "code" => 0,
                    "title" => "Les ventes  suivantes n'ont pas de Tva,veuillez l'ajouter Svp",
                    "form" => $this->renderView("AppBundle:commande:tva.form.collection.html.twig",array(
                        "commandesWithoutTva" => $commandeWithoutTva,
                        "commandes" => $commandes,
                        'actionPath' => $this->generateUrl('app_facture_new_one_collection')
                    ))
                ));
            }
            $this->getManager()->persist($facture, true);
            return new JsonResponse(array(
                "code" => 1,
                "msg" => "La facture N° ". $facture->numero() ." est générée avec succès"
            ));
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("collection/new", name="app_facture_new_collection")
     */
    public function newOneCollectionAction(Request $request) {
        if($request->isXmlHttpRequest() and $request->request->has("commande") and is_array($request->request->get("commande"))) {
            $commandeWithoutTva = array();
            $commandes = array();
            foreach ($request->request->get("commande") as $cmd){
                /** @var Commande $commande */
                $commande = $this->get('app.commande.manager')->find($cmd['id']);
                if($commande and is_null($commande->getFactureGlobal())){
                    if((is_null($commande->getTva()) or $commande->getTva() === 0) and !array_key_exists("tva",$cmd) )
                        $commandeWithoutTva[] = $commande;
                    else{
                        $commandes[] = $commande;
                        if(is_null($commande->getTva()) or $commande->getTva() === 0)
                            $commande->applyTva((float)$cmd['tva']);
                        /** @var Facture $facture */
                        $facture = $this->getManager()->create();
                        $facture->addCommande($commande)->setUser($this->getUser());
                        $commande->setFactureGlobal($facture);
                        $this->getManager()->persist($facture);
                    }
                }
            }
            if(count($commandeWithoutTva) > 0){
                return new JsonResponse(array(
                    "code" => 0,
                    "title" => "Les ventes  suivantes n'ont pas de Tva,veuillez l'ajouter Svp",
                    "form" => $this->renderView("AppBundle:commande:tva.form.collection.html.twig",array(
                        "commandesWithoutTva" => $commandeWithoutTva,
                        "commandes" => $commandes,
                        'actionPath' => $this->generateUrl('app_facture_new_collection')
                    ))
                ));
            }
            $this->getManager()->flush();
            $msg = "<ul> Les factures suivantes sont générées avec succès";
            foreach ($commandes as $cmd){
                $msg .= "<li style='text-align: left;'>Facture N° : ".$cmd->getFactureGlobal()->getId()."</li>";
            }
            $msg .= '</ul>';
            return new JsonResponse(array(
                "code" => 1,
                "msg" => $msg
            ));
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/remove/{id}", name="app_facture_remove")
     * @ParamConverter("facture", class="AppBundle:Facture")
     */
    public function removeAction(Facture $facture,Request $request) {
        /** @var Commande $commande */
        foreach ($facture->getCommandes() as $commande){
            $commande->setFactureGlobal();
            $facture->removeCommande($commande);
        }
        $this->getManager()->remove($facture,true);
        return new JsonResponse(array(
            "code" => 1,
            "msg" => "Facture supprimée avec succès",
        ));
    }

    /**
     * @Route("/print/{id}", name="app_facture_print")
     * @ParamConverter("facture", class="AppBundle:Facture")
     */
    public function printAction(Facture $facture,Request $request) {
        $html2pdfFactory = $this->getHtml2PdfFactory();
        $html = $this->renderView('pdf/facture.html.twig',array('facture' => $facture,"type"=>'pdf'));
        $html2pdf = $html2pdfFactory->create('P', 'A4', 'fr', true, 'UTF-8',3);
        $html2pdf->setDefaultFont("CenturyGothic");
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);
        return new Response($html2pdf->Output('facture_'.$facture->getId().'.pdf'), 200, array('Content-Type' => 'application/pdf'));
    }

    /**
     * @Route("/collection/print", name="app_facture_print_collection")
     */
    public function printCollectionAction(Request $request) {
       // $factures = $this->getManager()->collect($request->query->get('ids'),'c.dateLivraison','DESC');
        $factures = $this->getManager()->findBy(['id' => $request->query->get('ids')],['date' => 'DESC']);
        $count = count($factures);
        $nbrPages = intval(floor($count/10));
        $facturesInLastPage = $count % 10;
        $html2pdfFactory = $this->getHtml2PdfFactory();
        $html = $this->renderView('pdf/invoices.html.twig',array(
            'factures' => $factures,
            'facturesCount' => $count,
            'facturesInLastPage' => $facturesInLastPage,
            'pagesCount' => $nbrPages
        ));
        $html2pdf = $html2pdfFactory->create('P', 'A4', 'fr', true, 'UTF-8',3);
        $html2pdf->setDefaultFont("CenturyGothic");
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);
        return new Response($html2pdf->Output('factures.pdf'), 200, array('Content-Type' => 'application/pdf'));
    }

    /**
     * @return Html2pdfFactory
     */
    private function getHtml2PdfFactory(){
        return $this->get('html2pdf_factory');
    }

    /**
     * @return FactureManager
     */
    private function getManager(){
        return $this->get('app.facture.manager');
    }
}