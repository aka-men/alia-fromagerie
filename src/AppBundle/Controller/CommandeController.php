<?php

/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 08/04/2017
 * Time: 11:27
 */
namespace AppBundle\Controller;


use AppBundle\Entity\Commande;
use AppBundle\Entity\Facture;
use AppBundle\Entity\Paiement;
use AppBundle\Form\CommandeType;
use AppBundle\Form\PaiementType;
use AppBundle\Manager\CommandeManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Ensepar\Html2pdfBundle\Factory\Html2pdfFactory;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/ventes")
 */
class CommandeController extends Controller
{

    /**
     * @Route("", name="app_commande_index")
     */
    public function indexAction(Request $request) {
        if($request->isXmlHttpRequest() and $request->request->has('columns')){
            $pcram_sort = array(
                '',
                'c.id',
                'c.date',
                '',
                'c.montantHt',
                'c.tva',
                'c.montantTtc',
                '',
                '',
                '',
                '',
            );
            $start = $request->request->get("start");
            $length = $request->request->get("length");
            $criteres = $request->request->get("criteres",array());
            $sort = $pcram_sort[$request->request->get("order")[0]['column']];
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
            foreach ($requestResult['commandes'] as $commande){
                $resultat['data'][] = array(
                    "ligne" => $this->renderView('AppBundle:commande:td.html.twig',array(
                        'commande' => $commande,
                        'td' => 'ligne',
                        "ligne" => $ligne
                    )),
                    "date" => $this->renderView('AppBundle:commande:td.html.twig',array(
                        'commande' => $commande,
                        'td' => 'date'
                    )),
                    "client" => $this->renderView('AppBundle:commande:td.html.twig',array(
                        'commande' => $commande,
                        'td' => 'client'
                    )),
                    "id" => $this->renderView('AppBundle:commande:td.html.twig',array(
                        'commande' => $commande,
                        'td' => 'id'
                    )),
                    "ht" => $this->renderView('AppBundle:commande:td.html.twig',array(
                        'commande' => $commande,
                        'td' => 'ht'
                    )),
                    "tva" => $this->renderView('AppBundle:commande:td.html.twig',array(
                        'commande' => $commande,
                        'td' => 'tva'
                    )),
                    "facture" => $this->renderView('AppBundle:commande:td.html.twig',array(
                        'commande' => $commande,
                        'td' => 'facture'
                    )),
                    "ttc" => $this->renderView('AppBundle:commande:td.html.twig',array(
                        'commande' => $commande,
                        'td' => 'ttc'
                    )),
                    "frais" => $this->renderView('AppBundle:commande:td.html.twig',array(
                        'commande' => $commande,
                        'td' => 'frais'
                    )),
                    "total" => number_format($commande->getTotal(),2,'.',' '),
                    "avance" => $this->renderView('AppBundle:commande:td.html.twig',array(
                        'commande' => $commande,
                        'td' => 'avance'
                    )),
                    "avanceNum" => $commande->getAvance(),
                    "hasFacture" => !is_null($commande->getFactureGlobal()),
                    "reste" => number_format($commande->getReste(),2,'.',' '),
                    "action" => $this->renderView('AppBundle:commande:td.html.twig',array(
                        'commande' => $commande,
                        'td' => 'action'
                    )),
                );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        return $this->render("AppBundle:commande:index.html.twig",array(
            "modes" => $this->get('app.mode_payment.manager')->findBy(array(),array("ordre"=>"ASC")),
            "clients" => $this->get('app.client.manager')->findBy(array("isCommercial" => false)),
            "commerciales" => $this->get('app.client.manager')->findBy(array("isCommercial" => true)),
            "entreprises" => $this->get('app.entreprise.manager')->findAll(),
            "queryParams" => $request->query->all()
        ));
    }

    /**
     * @Route("/a-livre", name="app_commande_a_livrer")
     */
    public function indexShippingAction(Request $request) {
        if($request->isXmlHttpRequest() and $request->request->has('columns')){
            $pcram_sort = array(
                '',
                'c.id',
                'c.date'
            );
            $start = $request->request->get("start");
            $length = $request->request->get("length");
            $criteres = $request->request->get("criteres",array());
            $sort = $pcram_sort[$request->request->get("order")[0]['column']];
            $dir = $request->request->get("order")[0]['dir'];
            $criteres['dateLivraison'] = date_format(new \DateTime(),'d/m/Y');
            $requestResult = $this->getManager()->listeDataTable($criteres,$sort, $dir, $start, $length);
            $resultat = array(
                "data" => array(),
                'recordsFiltered' => $requestResult["totalFiltred"],
                'recordsTotal' => $requestResult["total"]
            );
            $ligne = 1;
            foreach ($requestResult['commandes'] as $commande){
                $resultat['data'][] = array(
                    "ligne" => $this->renderView('AppBundle:commande:shipping.td.html.twig',array(
                        'commande' => $commande,
                        'td' => 'ligne',
                        "ligne" => $ligne
                    )),
                    "date" => $this->renderView('AppBundle:commande:shipping.td.html.twig',array(
                        'commande' => $commande,
                        'td' => 'date'
                    )),
                    "client" => $this->renderView('AppBundle:commande:shipping.td.html.twig',array(
                        'commande' => $commande,
                        'td' => 'client'
                    )),
                    "id" => $this->renderView('AppBundle:commande:shipping.td.html.twig',array(
                        'commande' => $commande,
                        'td' => 'id'
                    )),
                    "total" => number_format($commande->getTotal(),2,'.',''),
                    "avance" => $this->renderView('AppBundle:commande:shipping.td.html.twig',array(
                        'commande' => $commande,
                        'td' => 'avance'
                    )),
                    "reste" => number_format($commande->getReste(),2,'.',''),
                );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/add", name="app_commande_add")
     */
    public function addAction(Request $request) {
        $commande = $this->getManager()->create();
        $form = $this->createForm(CommandeType::class,$commande);
        if($request->isXmlHttpRequest() and $request->isMethod('post') and $request->request->has('appbundle_commande')){
            $form->handleRequest($request);
                if($form->isValid()){
                    $commande->setUser($this->getUser());
                    if($request->request->has('create_invoice')){
                        $facture = $this->get('app.facture.manager')->create();
                        $facture->setCommande($commande)->setUser($this->getUser());
                        $commande->setFacture($facture);
                        $this->get('app.facture.manager')->persist($facture);
                    }
                    $this->getManager()->persist($commande,true);
                    return new JsonResponse(array(
                        "code" => 1,
                        "msg" => 'Commande (Id = '.$commande->getId().') ajoutée avec succès',
                        'content' => $this->renderView('pdf/bon_livraison.html.twig',array('commande' => $commande,"type"=>'printer')),
                        'id' => $commande->getId()
                    ));
                }else{
                    return new JsonResponse(array(
                        "code" => 0,
                        "msg" => (string)$form->getErrors(true)
                    ));
                }
        }
        $donnes = array(
            "formCommande" => $form->createView(),
            "clients" => $this->get('app.client.manager')->findBy(array("isCommercial" => false,'archive'=>false)),
            "commerciaux" => $this->get('app.client.manager')->findBy(array("isCommercial" => true,'archive'=>false)),
            "produitsAlia" => $this->get('app.produit.manager')->AliaForSel(),
            "produitsImportation" => $this->get('app.produit.manager')->ImportationForSel(),
            'entreprises' => $this->get('app.entreprise.manager')->findBy(array('archive'=>false)),
            'employes' => $this->get('app.employe.manager')->findBy(array('archive'=>false)),
            'modes' => $this->get('app.mode_payment.manager')->findBy(array('archive'=>false),array("ordre"=>"ASC")),
            "params" => $request->request->all()
        );
        if($request->isXmlHttpRequest() and $request->isMethod('post') and $request->request->has('commande_client_entreprise')){
            $donnes[$request->request->get('used_type')] = $this->get('app.'.$request->request->get('used_type').'.manager')->find($request->request->get('commande_client_entreprise'));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView('AppBundle:commande:form.html.twig',$donnes)
        ));
    }


    /**
     * @Route("/{id}/paiements/{paiement}/edit", name="app_commande_paiement_edit")
     * @ParamConverter("commande", class="AppBundle:Commande", options={"id" = "id"})
     * @ParamConverter("paiement", class="AppBundle:Paiement", options={"id" = "paiement"})
     */
    public function editPaiementAction(Commande $commande,Paiement $paiement,Request $request) {
        $form = $this->createForm(PaiementType::class,$paiement);
        if($request->isMethod("POST") and $request->request->has("appbundle_paiement") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()) {
                if($paiement->getImageCheque() and $paiement->getImageCheque()->getFile()){
                    if(is_null($paiement->getImageCheque()->getId())){
                        $paiement->getImageCheque()->preUpload();
                    }
                    $paiement->getImageCheque()->upload();
                }elseif ($paiement->getImageCheque() and is_null($paiement->getImageCheque()->getFile()) and array_key_exists('img_deleted',$request->request->get("appbundle_paiement"))){
                    $image = $paiement->getImageCheque();
                    $this->get('app.image.manager')->remove($image);
                    $paiement->setImageCheque();
                }
                $commande->preSave();
                $this->get('app.paiement.manager')->flush();
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Paiement modifié avec succès",
                    "paiement" =>  $this->get('jms_serializer')->serialize($paiement,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:paiement:form.html.twig",array(
                "formPaiement" => $form->createView(),
                "action"=>$this->generateUrl("app_commande_paiement_edit",array(
                    "id" => $commande->getId(),
                    "paiement" => $paiement->getId()
                )),
                "commande" => $commande,
                "paiement" => $paiement
            ))
        ));
    }

    /**
     * @Route("/{id}/paiements/{paiement}/remove", name="app_commande_paiement_remove")
     * @ParamConverter("commande", class="AppBundle:Commande", options={"id" = "id"})
     * @ParamConverter("paiement", class="AppBundle:Paiement", options={"id" = "paiement"})
     */
    public function removePaiementAction(Commande $commande,Paiement $paiement,Request $request) {
        $this->get('app.paiement.manager')->remove($paiement,true);
        $commande->removePaiement($paiement);
        $commande->preSave();
        $this->getManager()->flush();
        return new JsonResponse(array(
            "code" => 1,
            "msg" => "Paiement supprimé avec succès"
        ));
    }

    /**
     * @Route("/{id}/paiements/add", name="app_commande_paiement_add")
     * @ParamConverter("commande", class="AppBundle:Commande")
     */
    public function addPaiementAction(Commande $commande,Request $request) {
        $paiement = $this->get('app.paiement.manager')->create();
        $form = $this->createForm(PaiementType::class,$paiement);
        if($request->isMethod("POST") and $request->request->has("appbundle_paiement") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()) {
                $paiement->setUser($this->getUser());
                $commande->addPaiement($paiement);
                $commande->preSave();
                $this->get('app.paiement.manager')->persist($paiement, true);
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Paiement ajouté avec succès",
                    "paiement" =>  $this->get('jms_serializer')->serialize($paiement,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:paiement:form.html.twig",array(
                "formPaiement" => $form->createView(),
                "action"=>$this->generateUrl("app_commande_paiement_add",array(
                    "id" => $commande->getId()
                )),
                "commande" => $commande,
                "paiement" => $paiement
                ))
        ));
    }
    /**
     * @Route("/{id}/paiements", name="app_commande_paiement_liste")
     * @ParamConverter("commande", class="AppBundle:Commande")
     */
    public function listePaiementAction(Commande $commande,Request $request) {
        return new JsonResponse(array(
            "code" => 1,
            "html" => $this->renderView("AppBundle:paiement:liste.html.twig",array(
                "commande" => $commande
            ))
        ));
    }

    /**
     * @Route("/edit/{id}", name="app_commande_edit")
     * @ParamConverter("commande", class="AppBundle:Commande")
     */
    public function editAction(Commande $commande,Request $request) {
        $previousProduits = clone $commande->getProduits();
        $previousPaiements = clone $commande->getPaiements();
        $previousFrais = clone $commande->getFraisSupplementaires();
        $valuesPC= array();
        foreach ($previousProduits as $pc){
            $valuesPC[$pc->getId()] = $pc->getQuantite();
        }
        $form = $this->createForm(CommandeType::class,$commande);
        if($request->isMethod("POST") and $request->request->has("appbundle_commande") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()){
                foreach ($previousProduits as $pc){
                    if(!$commande->getProduits()->contains($pc))
                        $this->get('app.produit_commande.manager')->remove($pc);
                    else{
                        $pcActuel = $commande->getProduits()->get($commande->getProduits()->indexOf($pc));
                        $pc->getProduit()->increaseStock($valuesPC[$pc->getId()] - $pcActuel->getQuantite());
                    }
                }
                foreach ($previousPaiements as $p){
                    if(!$commande->getPaiements()->contains($p))
                        $this->get('app.paiement.manager')->remove($p);
                }
                foreach ($previousFrais as $fc){
                    if(!$commande->getFraisSupplementaires()->contains($fc))
                        $this->get('app.frais_supp.manager')->remove($fc);
                }
                foreach ($commande->getPaiements() as $p){
                    if($p->getImageCheque() and $p->getImageCheque()->getId() and $p->getImageCheque()->getFile())
                        $p->getImageCheque()->upload();
                }
                $commande->preSave();
                $this->getManager()->flush();
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Vente modifiée avec succès",
                    'content' => $this->renderView('pdf/bon_livraison.html.twig',array('commande' => $commande,"type"=>'printer')),
                    'id' => $commande->getId()
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:commande:form.edit.html.twig",array(
                "formCommande" => $form->createView(),
                'commande' => $commande,
                'modes' => $this->get('app.mode_payment.manager')->findBy(array('archive'=>false),array("ordre"=>"ASC")),
                "clients" => $this->get('app.client.manager')->findBy(array("isCommercial" => false,'archive'=>false)),
                'employes' => $this->get('app.employe.manager')->findBy(array('archive'=>false)),
                "commerciaux" => $this->get('app.client.manager')->findBy(array("isCommercial" => true,'archive'=>false)),
                'entreprises' => $this->get('app.entreprise.manager')->findBy(array('archive'=>false))
        ))));
    }

    /**
     * @Route("/add-produits", name="app_commande_add_produits")
     */
    public function addProduitsAction(Request $request) {
        if($request->isMethod("POST") and $request->request->has("produits") and $request->isXmlHttpRequest()){
                return new JsonResponse(array(
                    "code" => 1,
                    "lines" => $this->renderView("AppBundle:commande:form.produits.html.twig",array(
                        "params" => $request->request->all(),
                        "response" => 'lines',
                        "produits" => new ArrayCollection(array_merge((array)$this->get('app.produit.manager')->AliaForSel(),(array)$this->get('app.produit.manager')->ImportationForSel()))
                    )),
                    "nbrLines" => count($request->request->get("produits",array()))
                ));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:commande:form.produits.html.twig",array(
                "params" => $request->query->all(),
                "response" => 'form',
                "produits" => new ArrayCollection(array_merge((array)$this->get('app.produit.manager')->AliaForSel(),(array)$this->get('app.produit.manager')->ImportationForSel()))
            ))));
    }

    /**
     * @Route("/add-echantillons", name="app_commande_add_echantillons")
     */
    public function addEchantillonsAction(Request $request) {
        if($request->isMethod("POST") and $request->request->has("echantillons") and $request->isXmlHttpRequest()){
            return new JsonResponse(array(
                "code" => 1,
                "lines" => $this->renderView("AppBundle:commande:form.echantillons.html.twig",array(
                    "params" => $request->request->all(),
                    "response" => 'lines',
                    "produits" => new ArrayCollection(array_merge((array)$this->get('app.produit.manager')->AliaForSel(),(array)$this->get('app.produit.manager')->ImportationForSel()))
                )),
                "nbrLines" => count($request->request->get("echantillons",array()))
            ));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:commande:form.echantillons.html.twig",array(
                "params" => $request->query->all(),
                "response" => 'form',
                "produits" => new ArrayCollection(array_merge((array)$this->get('app.produit.manager')->AliaForSel(),(array)$this->get('app.produit.manager')->ImportationForSel()))
            ))));
    }

    /**
     * @Route("/remove/{id}", name="app_commande_remove")
     * @ParamConverter("commande", class="AppBundle:Commande")
     */
    public function removeAction(Commande $commande,Request $request) {
        $this->getManager()->remove($commande,true);
        return new JsonResponse(array(
            "code" => 1,
            "msg" => "Vente supprimée avec succès",
        ));
    }

    /**
     * @Route("facture/remove/{id}", name="app_commande_facture_remove")
     * @ParamConverter("commande", class="AppBundle:Commande")
     */
    public function removeInvoiceAction(Commande $commande,Request $request) {
        if($commande->getFactureGlobal()){
            /** @var Facture $facture */
            $facture = $commande->getFactureGlobal();
            $commande->setFactureGlobal();
            $this->getManager()->flush();
            $facture->removeCommande($commande);
            if($facture->getCommandes()->count() === 0)
                $this->get('app.facture.manager')->remove($facture,flush());
            return new JsonResponse(array(
                "code" => 1,
                "msg" => "modification enregistrée",
            ));
        }
        throw $this->createNotFoundException('Cette vente n\'a pas de facture !!');
    }

    /**
     * @Route("/find-costom", name="app_commande_find_custom")
     */
    public function findCustomAction(Request $request) {
        if($request->isXmlHttpRequest() and $request->request->has('criteres') and is_array($request->request->get('criteres'))){
            $commandes = $this->getManager()->findCustom($request->request->get('criteres'));
            return new JsonResponse(array(
                'code' => 1,
                'content' => $this->renderView('AppBundle:commande:liste_latest.html.twig',array('commandes' => $commandes))
            ));
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/bon/{id}", name="app_commande_bon_livraison")
     * @ParamConverter("commande", class="AppBundle:Commande")
     */
    public function bonLivraisonAction(Commande $commande,Request $request) {
        if($request->isXmlHttpRequest()){
            return new JsonResponse(array(
                'code' => 1,
                'content' => $this->renderView('pdf/bon_livraison.html.twig',array('commande' => $commande,"type"=>'printer')),
                'id' => $commande->getId()
            ));
        }
        $heigth = 196 + $commande->getProduits()->count() * 5;
        if($commande->getFraisSupplementaires()->count() > 0)
            $heigth += $commande->getFraisSupplementaires()->count() * 5;
        if(is_null($commande->getEmploye()))
            $heigth += 5;
        if($commande->getPaiements()->count() > 0)
            $heigth += $commande->getPaiements()->count() * 5 + 10;
        else
            $heigth += 3;
        if($commande->getTva() and $commande->getTva() > 0)
            $heigth += 10;
        $html2pdfFactory = $this->getHtml2PdfFactory();
        $html = $this->renderView('pdf/bon_livraison.html.twig',array('commande' => $commande,"type"=>'pdf'));
        $html2pdf = $html2pdfFactory->create('P', array(80,$heigth), 'fr', true, 'UTF-8', array(2, 0, 2, 0));
        $html2pdf->setDefaultFont("CenturyGothic");
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);
        return new Response($html2pdf->Output('bon_'.$commande->getId().'.pdf'), 200, array('Content-Type' => 'application/pdf'));
    }

    /**
     * @Route("/paiements/add", name="app_commande_paiements_add")
     */
    public function addCollectionPaiementAction(Request $request) {
        $paiement = $this->get('app.paiement.manager')->create();
        $form = $this->createForm(PaiementType::class,$paiement);
        if($request->isMethod("POST") and $request->request->has("commandes")  and is_array($request->request->get("commandes")) and count($request->request->get("commandes")) > 0  and $request->request->has("appbundle_paiement") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()) {
                $paiement->setUser($this->getUser());
                $parent = clone $paiement;
                $montant = $paiement->getPrix();
                $commandes = $this->getManager()->findCustom(array("ids"=>$request->request->get("commandes")),100,"ASC");
                foreach ($commandes as $commande){
                    if($montant > 0 and $commande->getReste() > 0){
                          $p = clone $paiement;
                          $p->setCommande($commande);
                          $prix = $commande->getReste() <= $montant ? $commande->getReste() : $montant;
                          $p->setPrix($prix);
                          $p->setParent($parent);
                          $this->get('app.paiement.manager')->persist($p);
                          $montant -= $prix;
                    }else
                        break;
                }
                $this->get('app.paiement.manager')->persist($parent);
                $this->get('app.paiement.manager')->flush();
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Paiements ajoutés avec succès",
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        if($request->isMethod("POST") and $request->request->has("commandes") and is_array($request->request->get("commandes")) and count($request->request->get("commandes")) > 0 and $request->isXmlHttpRequest()){
            $commandes = $this->getManager()->findCustom(array("ids"=>$request->request->get("commandes")),100,"ASC");
            return new JsonResponse(array(
                "code" => 1,
                "form" => $this->renderView("AppBundle:paiement:form.html.twig",array(
                    "formPaiement" => $form->createView(),
                    "action"=>$this->generateUrl("app_commande_paiements_add"),
                    "commandes" => $commandes,
                    "paiement" => $paiement
                ))
            ));
        }
        throw $this->createNotFoundException("L'URL demandée n'existe pas !!");
    }

    /**
     * @Route("/statut-client", name="app_commande_statut_client")
     */
    function clientStatutAction(Request $request){
        if($request->isMethod('post') and $request->request->has('statut')){
            $reportCommandes = $this->getManager()->report($request->request->get('statut',[]));
            $className = array_key_exists('client',$request->request->get('statut',[])) ? 'client' : (array_key_exists('entreprise',$request->request->get('statut',[])) ? 'entreprise' : 'undefined');
            $objet = $this->get("app.$className.manager")->find(array_key_exists('client',$request->request->get('statut',[])) ? $request->request->get('statut')['client'] : (array_key_exists('entreprise',$request->request->get('statut',[])) ? $request->request->get('statut')['entreprise'] : 0));
            $html2pdfFactory = $this->getHtml2PdfFactory();
            $html = $this->renderView('AppBundle:commande:statut_client.html.twig',array(
                'criteres' => $request->request->get('statut',[]),
                'reportCommandes' => $reportCommandes,
                $className => $objet
            ));

            $html2pdf = $html2pdfFactory->create('P', 'A4', 'fr', true, 'UTF-8',array('0','0','0','0'));
            $html2pdf->setDefaultFont("CenturyGothic");
            $html2pdf->pdf->SetDisplayMode('real');
            $html2pdf->writeHTML($html);
            return new Response($html2pdf->Output('Statut_client.pdf'), 200, array('Content-Type' => 'application/pdf'));
        }
        $managerName = 'app.'.$request->query->get('type','none').'.manager';
        $manager = $this->get($managerName);
        $objet = $manager->find($request->query->get('id',0));
        if(is_null($objet))
            return $this->createNotFoundException('Objet introuvable !!');
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView('AppBundle:commande:form_statut.html.twig',[
                $request->query->get('type','none') => $objet
            ])
        ));
    }

    /**
     * @Route("/collection/remove", name="app_commande_remove_collection")
     */
    public function removeCollectionAction(Request $request) {
        $commandes = $this->getManager()->findBy(['id'=>$request->request->get('commandes')]);
        foreach ($commandes as $commande){
            $this->getManager()->remove($commande);
        }
        $this->getManager()->flush();
        return new JsonResponse(array(
            "code" => 1,
            "msg" => "Ventes supprimées avec succès",
        ));
    }

    /**
     * @Route("/collection/print", name="app_commande_print_collection")
     */
    public function printCollectionAction(Request $request) {
        $bons = $this->getManager()->findByIds($request->query->get('ids'),'c.dateLivraison','DESC');
        $count = count($bons);
        $nbrPages = intval(floor($count/20));
        $bonsInLastPage = $count % 20;
        $html2pdfFactory = $this->getHtml2PdfFactory();
        $html = $this->renderView('pdf/bons.html.twig',array(
            'bons' => $bons,
            'bonsCount' => $count,
            'commandesInLastPage' => $bonsInLastPage,
            'pagesCount' => $nbrPages
        ));
        $html2pdf = $html2pdfFactory->create('P', 'A4', 'fr', true, 'UTF-8',3);
        $html2pdf->setDefaultFont("CenturyGothic");
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);
        return new Response($html2pdf->Output('bons.pdf'), 200, array('Content-Type' => 'application/pdf'));
    }


    /**
     * @return Html2pdfFactory
     */
    private function getHtml2PdfFactory(){
        return $this->get('html2pdf_factory');
    }


    /**
     * @return CommandeManager
     */
    private function getManager(){
        return $this->get('app.commande.manager');
    }
}